<?php

namespace App\Controller\Client;

use App\Repository\User\StudentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_CLIENT')]
#[Route(name: 'app_client_')]
final class MainController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(StudentRepository $studentRepository): Response
    {

        if ($this->isGranted('ROLE_STUDENT')) {
            return $this->render('student/main/index.html.twig', []);
        }


        return $this->render('mentor/main/dashboard.html.twig', [
            "students" => $studentRepository -> findAll()
        ]);
    }
}
