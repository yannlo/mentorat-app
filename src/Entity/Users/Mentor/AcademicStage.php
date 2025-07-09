<?php

namespace App\Entity\Users\Mentor;

use App\Entity\Enums\Level;
use App\Entity\Users\Mentor\Mentor;
use App\Entity\Utils\AbstractTimestamp;
use App\Repository\Users\Mentor\AcademicStageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AcademicStageRepository::class)]
class AcademicStage extends AbstractTimestamp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        groups: ["mentor:academic-stages"],
        message: 'School name cannot be blank'
    )]
    #[Assert\Length(
        min: 3,
        minMessage: 'School name must be at least {{ limit }} characters long',
        max: 255,
        maxMessage: 'School name cannot be longer than {{ limit }} characters',
        groups: ["mentor:academic-stages"]
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s]+$/',
        message: 'School name can only contain letters, numbers, and spaces.',
        groups: ["mentor:academic-stages"],
    )]
    private ?string $schoolName = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\NotBlank(message: 'Start year cannot be blank')]
    #[Assert\GreaterThanOrEqual(
        groups: ["mentor:academic-stages"],
        value: 1900,
        message: 'Start year must be after or equal to 1900.'
    )]
    #[Assert\LessThanOrEqual(
        groups: ["mentor:academic-stages"],
        value: 'today',
        message: 'Start year cannot be in the future.'
    )]
    private ?int $startYear = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\GreaterThanOrEqual(
        groups: ["mentor:academic-stages"],
        propertyPath: 'startYear',
        message: 'End year must be greater than or equal to start year.'
    )]
    #[Assert\LessThanOrEqual(
        groups: ["mentor:academic-stages"],
        value: 'today',
        message: 'End year cannot be in the future.'
    )]
    #[Assert\Range(
        groups: ["mentor:academic-stages"],
        min: 1900,
        max: 2100,
        notInRangeMessage: 'End year must be between {{ min }} and {{ max }}.'
    )]
    private ?int $endYear = null;

    #[ORM\Column(enumType: Level::class)]
    #[Assert\NotBlank(
        groups: ["mentor:academic-stages"],
        message: 'Level cannot be blank'
    )]
    private ?Level $level = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        groups: ["mentor:academic-stages"],
        message: 'Degree name cannot be blank'
    )]
    #[Assert\Length(
        groups: ["mentor:academic-stages"],
        min: 3,
        minMessage: 'Degree name cannot be shorter than {{ limit }} characters',
        max: 255,
        maxMessage: 'Degree name cannot be longer than {{ limit }} characters'
    )]
    #[Assert\Regex(
        groups: ["mentor:academic-stages"],
        pattern: '/^[a-zA-Z0-9\s]+$/',
        message: 'Degree name can only contain letters, numbers, and spaces.'
    )]
    private ?string $degreeName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        groups: ["mentor:academic-stages"],
        min: 10,
        minMessage: 'Description cannot be shorter than {{ limit }} characters',
        max: 1000,
        maxMessage: 'Description cannot be longer than {{ limit }} characters'
    )]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'academicStages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mentor $mentor = null;

    // #[ORM\ManyToOne(inversedBy: 'academicStages')]
    // private ?Mentor $mentor = null;



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

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    public function setStartYear(int $startYear): static
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): static
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
