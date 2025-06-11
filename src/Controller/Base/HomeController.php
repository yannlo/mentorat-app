<?php

namespace App\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        if ($this->isGranted('ROLE_CLIENT')) {
            return $this->redirectToRoute('app_client_dashboard');
        }

        return $this->render('base/home/index.html.twig', []);
    }

    #[Route('/become-mentor', name: 'app_mentoring')]
    public function mentoring(): Response
    {
        if ($this->isGranted('ROLE_CLIENT')) {
            return $this->redirectToRoute('app_client_dashboard');
        }
        return $this->render('base/home/mentoring.html.twig', []);
    }
}
