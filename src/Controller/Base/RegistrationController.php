<?php

namespace App\Controller\Base;

use App\Entity\User\BaseClientUser;
use App\Entity\User\Mentor;
use App\Entity\User\Student;
use App\Entity\User\User;
use App\Form\RegistrationForm;
use App\Form\User\MentorType;
use App\Form\User\StudentForm;
use App\Form\User\StudentType;
use App\Repository\User\UserRepository;
use App\Security\AppAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/register', name: 'app_register_')]
class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier) {}


    #[Route('-student', name: 'student')]
    public function registerStudent(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $student->setPassword($userPasswordHasher->hashPassword($student, $plainPassword));

            $entityManager->persist($student);
            $entityManager->flush();

            $student->setRoles(["ROLE_STUDENT"]);
            if ($this->getParameter("kernel.environment") === 'dev') {
                $student->setIsVerified(true);
            } else {
                $this->SendEmail($student, 'registration/confirmation_email.html.twig');
            }

            $this->addFlash('success', 'Student registered successfully. You can now log in.');
            return $security->login($student, AppAuthenticator::class, 'main');
        }
        return $this->render('base/registration/student.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('-mentor', name: 'mentor')]
    public function registerMentor(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $mentor = new Mentor();
        $form = $this->createForm(MentorType::class, $mentor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $mentor->setPassword($userPasswordHasher->hashPassword($mentor, $plainPassword));
            // Set the role to mentor
            $mentor->setRoles(["ROLE_MENTOR"]);
            $entityManager->persist($mentor);
            $entityManager->flush();

            if ($this->getParameter("kernel.environment") === 'dev') {
                $mentor->setIsVerified(true);
            } else {
                $this->SendEmail($mentor, 'registration/confirmation_email.html.twig');
            }

            $this->addFlash('success', 'Mentor registered successfully. You can now log in.');
            return $security->login($mentor, AppAuthenticator::class, 'main');
        }

        return $this->render('base/registration/mentor.html.twig', [
            'form' => $form,
        ]);
    }

    private function SendEmail(BaseClientUser $user, string $template): void
    {
        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
                ->from(new Address('bot@mentor-app.ci', 'Mail Bot - MentorApp'))
                ->to((string) $user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_client_dashboard');
    }
}
