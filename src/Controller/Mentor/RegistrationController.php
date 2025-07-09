<?php

namespace App\Controller\Mentor;

use App\Entity\Users\Mentor\Mentor;
use App\Entity\Enums\MentorRegisterStep as RegisterStep;
use App\Form\Users\Mentor\AboutForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_MENTOR")]
final class RegistrationController extends AbstractController
{
    #[Route(
        '/register-mentor/{stepUri}',
        name: 'app_mentor_register',
        requirements: [
            "stepUri" => "[a-z\-]+"
        ]
    )]
    public function index(
        string $stepUri,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        /** @var Mentor $mentor */
        $mentor = $this->getUser();

        try {
            $currentStep = RegisterStep::getStepByUri($stepUri);
        } catch (\InvalidArgumentException) {
            throw $this->createNotFoundException('Étape d\'inscription inconnue.');
        }

        if ($currentStep->getOrder() > $mentor->getRegisterStep()->getOrder()) {
            return $this->redirectToRoute(
                'app_mentor_register',
                ["stepUri" => $mentor->getRegisterStep()->getUri()]
            );
        }

        $formClass = $currentStep->getFormClass();

        $form = $this->createForm(
            type: $formClass,
            data: $mentor,
            options: $formClass ===  AboutForm::class ? [
                "is_connected" => (bool) $this->getUser()
            ] : []
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nextStep = $currentStep->getNext();
            if ($nextStep->getOrder() > $mentor->getRegisterStep()->getOrder()) {
                $mentor->setRegisterStep(
                    $nextStep
                );
            }
            $entityManager->persist($mentor);
            $entityManager->flush();


            if ($mentor->getRegisterStep() === RegisterStep::COMPLETED) {
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
                ["stepUri" => $currentStep->getNext()->getUri()]
            );
        }

        return $this->render('mentor/registration/index.html.twig', [
            'form' => $form,
            'form_template' => $currentStep->getTemplate(),
            'form_steps' => [
                "all" =>  array_slice(RegisterStep::all(), 0, -1),
                "current" => $currentStep,
                "mentor" => $mentor->getRegisterStep()
            ]
        ]);
    }


    private function generateSteps(Mentor $mentor, RegisterStep $pageStep): array
    {
        $steps = [];
        foreach (RegisterStep::all() as $step) {
            $isDone = $mentor->getRegisterStep()->getOrder()  >= $step->getOrder();
            $steps[] = [
                "label" => $step->getLabel(),
                "value" => $step->value,
                "done" => $isDone,
                "current" => $pageStep->getOrder() == $step->getOrder(),
                "afterCurrent" => $step->getOrder() > $pageStep->getOrder(),
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
