<?php

namespace App\DataFixtures;

use App\Entity\Enum\StudentClass;
use App\Entity\User\Mentor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[When(env: 'dev')]
class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $mentor = new Mentor();
        $mentor->setEmail('mentor@example.com')
            ->setPassword(
                $this->passwordHasher
                    ->hashPassword($mentor, 'password123')
            ) // In a real application, use a password encoder
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setRoles(['ROLE_MENTOR'])
            ->setBirthdate(new \DateTimeImmutable('2000-01-01'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->addAcademicStage(
                (new \App\Entity\AcademicStage())
                    ->setSchoolName('Example High School')
                    ->setStartYear('2017')
                    ->setEndYear('2019')
                    ->setLevel(\App\Entity\Enum\Level::HIGH_SCHOOL)
                    ->setDegreeName('High School Diploma')
                    ->setDescription('This is an example academic stage for high school.')
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable())
            )
            ->addAcademicStage(
                (new \App\Entity\AcademicStage())
                    ->setSchoolName('Example University')
                    ->setStartYear('2020')
                    ->setEndYear('2024')
                    ->setLevel(\App\Entity\Enum\Level::UNDERGRADUATE)
                    ->setDegreeName('Bachelor of Science')
                    ->setDescription('This is an example academic stage for university.')
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable())
            )
            ->addCertificate((new \App\Entity\Certificate())->setName('Example Certificate')
                    ->setDescription('This is an example certificate.')
                    ->setIssueDate(new \DateTimeImmutable('2024-01-01'))
                    ->setIssuer('Example Issuer')
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable()))
            ->addCertificate((new \App\Entity\Certificate())->setName('Another Certificate')
                    ->setDescription('This is another example certificate.')
                    ->setIssueDate(new \DateTimeImmutable('2024-02-01'))
                    ->setIssuer('Another Issuer')
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable()));

        $student = new \App\Entity\User\Student();
        $student->setEmail('student@example.com')
            ->setPassword(
                $this->passwordHasher
                    ->hashPassword($student, 'password123')
            ) // In a real application, use a password encoder
            ->setFirstName('Jane')
            ->setLastName('Smith')
            ->setRoles(['ROLE_STUDENT'])
            ->setBirthdate(new \DateTimeImmutable('2000-01-01'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setSchoolName('Example High School')
            ->setLevel(\App\Entity\Enum\Level::HIGH_SCHOOL)
            ->setClassname(StudentClass::FIRST)
        ;

        $manager->persist($mentor);
        $manager->persist($student);
        $manager->flush();
    }
}
