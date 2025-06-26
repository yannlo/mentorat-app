<?php

namespace App\Controller\Base;

use App\Entity\Enums\Day as EnumDay;
use App\Entity\Users\Mentor\Day;
use App\Entity\Users\Mentor\TimePeriod;
use App\Form\Types\AvailableDaysType;
use App\Form\Types\CertificateType;
use App\Form\Types\DayType;
use App\Form\Types\PeriodsType;
use App\Form\Users\Mentor\AvailableAndPriceForm;
use App\Form\Users\Mentor\CertificationsForm;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\Users\Mentor\DayRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/test-widget', name: 'app_test_widget', env: "dev")]
    public function test(Request $request, DayRepository $dr): Response
    {

        $form = $this->createForm(AvailableDaysType::class, [
            (new Day())
                ->setName(EnumDay::MONDAY)
                ->addPeriod(
                    (new TimePeriod())
                        ->setStart(10)
                        ->setEnd(12)
                )
                ->addPeriod(
                    (new TimePeriod())
                        ->setStart(16)
                        ->setEnd(end: 21)
                ),
            (new Day())
                ->setName(EnumDay::THURSDAY)
                ->addPeriod(
                    (new TimePeriod())
                        ->setStart(7)
                        ->setEnd(12)
                )
                ->addPeriod(
                    (new TimePeriod())
                        ->setStart(15)
                        ->setEnd(end: 17)
                ),
        ]);

        $form->handleRequest($request);

        return $this->render('base/home/test.html.twig', [
            "form" => $form
        ]);
    }
}
