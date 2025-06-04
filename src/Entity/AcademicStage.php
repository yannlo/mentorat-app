<?php

namespace App\Entity;

use App\Entity\Enum\Level;
use App\Entity\User\Mentor;
use App\Repository\AcademicStageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcademicStageRepository::class)]
class AcademicStage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $schoolName = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTime $startYear = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $endYear = null;

    #[ORM\Column(enumType: Level::class)]
    private ?Level $level = null;

    #[ORM\Column(length: 255)]
    private ?string $degreeName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'academicStages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mentor $mentor = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchoolName(): ?string
    {
        return $this->schoolName;
    }

    public function setSchoolName(string $schoolName): static
    {
        $this->schoolName = $schoolName;

        return $this;
    }

    public function getStartYear(): ?\DateTime
    {
        return $this->startYear;
    }

    public function setStartYear(\DateTime $startYear): static
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndYear(): ?\DateTimeImmutable
    {
        return $this->endYear;
    }

    public function setEndYear(\DateTimeImmutable $endYear): static
    {
        $this->endYear = $endYear;

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

    public function getDegreeName(): ?string
    {
        return $this->degreeName;
    }

    public function setDegreeName(string $degreeName): static
    {
        $this->degreeName = $degreeName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMentor(): ?Mentor
    {
        return $this->mentor;
    }

    public function setMentor(?Mentor $mentor): static
    {
        $this->mentor = $mentor;

        return $this;
    }

}
