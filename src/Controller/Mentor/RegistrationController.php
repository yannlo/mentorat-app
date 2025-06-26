<?php

namespace App\Controller\Mentor;

use App\Entity\Users\Mentor\Mentor;
use App\Form\Users\MentorType;
use App\Entity\Enums\MentorRegisterStep;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_MENTOR")]
final class RegistrationController extends AbstractController
{
    #[Route(
        '/register-mentor/{step}',
        name: 'app_mentor_register',
    )]
    public function index(
        string $step,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        /** @var Mentor $mentor */
        $mentor = $this->getUser();

        if ($mentor->getRegisterStep() === MentorRegisterStep::COMPLETED) {
            return $this->redirectToRoute('app_client_dashboard');
        }

        $currentStep = MentorRegisterStep::tryFrom($step);
        if (!$currentStep) {
            throw $this->createNotFoundException('Étape de registre mentor inconnue.');
        }

        $steps = $this->generateSteps($mentor, $currentStep);


        $formClass = "App\Form\Users\Mentor\\" . ucfirst(
            str_replace("Step", "", $step)
        ) . "Form";

        $template = "mentor/form/_"
            . str_replace("Step", "", $step)
            .".html.twig"
       ;
        if (!class_exists($formClass)) {
            return $this->redirectToRoute('app_mentor_register', ["step" => $mentor->getRegisterStep()->getNext()->value]);
        }


        $form = $this->createForm($formClass, $mentor);
        $form->handleRequest($request);

        // Ensure the mentor's register step matches the current step in the URL
        if ($form->isSubmitted() && $form->isValid()) {

            $currentStep = $mentor->getRegisterStep();
            $nextStep = $currentStep->getNext();


            if (!is_null($nextStep) && $currentStep -> getOrder() > $mentor->getRegisterStep()->getOrder()) {
                $mentor->setRegisterStep(
                    $nextStep
                );
            }

            $entityManager->persist($mentor);
            $entityManager->flush();

            if (!is_null($nextStep)) {
                $this->addFlash(
                    'success',
                    sprintf(
                        'Vous avez terminer la complétion de votre compte',
                        $currentStep->getLabel()
                    )
                );

                return $this->redirectToRoute('app_client_dashboard');
            }

            $this->addFlash('success', sprintf(
                'Étape "%s" complétée avec succès.',
                $currentStep->getLabel()
            ));

            return $this->redirectToRoute(
                'app_mentor_register',
                ["step" => $mentor->getRegisterStep()->value]
            );
        }

        return $this->render('mentor/registration/index.html.twig', [
            'form' => $form,
            'form_template' => $template,
            'form_steps' => $steps
        ]);
    }


    private function generateSteps(Mentor $mentor, MentorRegisterStep $pageStep ): array
    {
        $steps = [];
        foreach (MentorRegisterStep::all() as $step) {
            $isDone = $mentor->getRegisterStep()->getOrder()  >= $step->getOrder();
            $steps[] = [
                "label" => $step->getLabel(),
                "value" =>$step->value,
                "done" => $isDone,
                "current" => $pageStep->getOrder() == $step->getOrder(),
                "afterCurrent" => $step->getOrder() > $pageStep->getOrder() ,
                "last" => false
            ];
        }

        array_pop($steps);

        if (!empty($steps)) {
            $steps[count($steps) - 1]["last"] = true;
        }

        return $steps;
    }
}
