<?php

namespace App\Entity\Users\Mentor;

use App\Entity\Users\Mentor\Mentor;
use App\Entity\Utils\AbstractTimestamp;
use App\Repository\Users\Mentor\CertificateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CertificateRepository::class)]
class Certificate extends AbstractTimestamp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        groups:["mentor:certificates"],
        message: 'Certificate name cannot be blank')]
    #[Assert\Length(
        groups:["mentor:certificates"],
        min: 3,
        minMessage: 'Certificate name cannot be short than {{ limit }} characters',
        max: 255,
        maxMessage: 'Certificate name cannot be longer than {{ limit }} characters'
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotBlank(
        groups:["mentor:certificates"],
        message: 'Issue date cannot be blank')]
    #[Assert\LessThanOrEqual(
        groups:["mentor:certificates"],
        value: 'today',
        message: 'Issue date cannot be in the future.'
    )]
    private ?\DateTimeImmutable $issueDate = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        groups:["mentor:certificates"],
        message: 'Issuer cannot be blank')]
    #[Assert\Length(
        groups:["mentor:certificates"],
        min: 3,
        minMessage: 'Issuer name cannot be short than {{ limit }} characters',
        max: 255,
        maxMessage: 'Issuer name cannot be longer than {{ limit }} characters'
    )]
    private ?string $issuer = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        groups:["mentor:certificates"],
        min: 3,
        minMessage: 'Description cannot be short than {{ limit }} characters',
        max: 1000,
        maxMessage: 'Description cannot be longer than {{ limit }} characters'
    )]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'certificates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mentor $mentor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIssueDate(): ?\DateTimeImmutable
    {
        return $this->issueDate;
    }

    public function setIssueDate(\DateTimeImmutable $issueDate): static
    {
        $this->issueDate = $issueDate;

        return $this;
    }

    public function getIssuer(): ?string
    {
        return $this->issuer;
    }

    public function setIssuer(string $issuer): static
    {
        $this->issuer = $issuer;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
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
