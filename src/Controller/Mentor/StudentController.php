<?php

namespace App\Controller\Mentor;

use App\Entity\Users\Student;
use App\Repository\Users\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_MENTOR")]
#[Route('', name: 'app_mentor_')]

final class StudentController extends AbstractController
{
    #[Route('/my-students/{publicIdSimplify}', name: 'student_details')]
    public function index(Student $student): Response
    {
        return $this->render('mentor/main/student.html.twig', [
            "student" => $student
        ]);
    }
}
