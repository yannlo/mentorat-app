<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_CLIENT')]
#[Route(name: 'app_client_')]
final class MainController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {

        if ($this->isGranted('ROLE_STUDENT')) {
            return $this->render('student/main/index.html.twig', []);
        }

        return $this->render('mentor/main/index.html.twig', []);
    }
}
