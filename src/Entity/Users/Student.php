<?php

namespace App\Entity\Users;

use App\Entity\Enums\Level;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Enums\StudentClass;
use App\Repository\User\StudentRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends BaseClientUser
{
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'School name cannot be blank')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'School name cannot be longer than {{ limit }} characters'
    )]
    private ?string $schoolName = null;

    #[ORM\Column(enumType: Level::class)]
    #[Assert\NotBlank(message: 'Level cannot be blank')]
    private ?Level $level = null;

    #[ORM\Column(enumType: StudentClass::class)]
    #[Assert\NotBlank(message: 'Class name cannot be blank')]
    #[Assert\Expression(
        'this.getClassname() in this.getLevel().classes()',
        message: 'This class is not available for the selected level.'
    )]
    private ?StudentClass $classname = null;


    public function getSchoolName(): ?string
    {
        return $this->schoolName;
    }

    public function setSchoolName(string $schoolName): static
    {
        $this->schoolName = $schoolName;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(Level $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getClassname(): ?StudentClass
    {
        return $this->classname;
    }

    public function setClassname(StudentClass $classname): static
    {
        $this->classname = $classname;

        return $this;
    }
}
