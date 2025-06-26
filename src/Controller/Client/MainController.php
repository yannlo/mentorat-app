<?php

namespace App\Controller\Client;

use App\Entity\Users\Mentor\Mentor;
use App\Entity\Enums\MentorRegisterStep;
use App\Repository\Users\StudentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_CLIENT')]
#[Route(name: 'app_client_')]
final class MainController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(Request $request, StudentRepository $studentRepository): Response
    {
        $user = $request->getUser();
        if (
            ($user instanceof Mentor)
            && $user->getRegisterStep() !== MentorRegisterStep::COMPLETED
        ) {
            return $this->redirectToRoute('app_mentor_register', ["step" => $user->getRegisterStep()->getNext()->value]);
        }

        if ($this->isGranted('ROLE_STUDENT')) {
            return $this->render('student/main/index.html.twig', []);
        }


        return $this->render('mentor/main/dashboard.html.twig', [
            "students" => $studentRepository->findAll()
        ]);
    }
}
