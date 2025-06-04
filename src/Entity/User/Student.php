<?php

namespace App\Entity\User;

use App\Entity\Enum\StudentClass;
use App\Entity\Enum\Level;
use App\Repository\User\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends User
{

    #[ORM\Column(length: 255)]
    private ?string $schoolName = null;

    #[ORM\Column(enumType: Level::class)]
    private ?Level $level = null;

    #[ORM\Column(enumType: StudentClass::class)]
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
