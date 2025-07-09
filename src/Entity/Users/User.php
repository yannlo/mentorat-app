<?php

namespace App\Entity\Users;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Users\Mentor\Mentor;
use App\Entity\Utils\AbstractTimestamp;
use App\Repository\Users\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\InheritanceType(value: 'JOINED')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap([
    'manager' => Manager::class,
    'moderator' => Moderator::class,
    'student' => Student::class,
    'mentor' => Mentor::class,
])]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé par un autre compte.')]
class User extends AbstractTimestamp implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Email cannot be blank')]
    #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
    #[Assert\Length(
        max: 180,
        maxMessage: 'Email cannot be longer than {{ limit }} characters'
    )]
    protected ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    protected array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    protected ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'First name cannot be blank')]
    #[Assert\Length(
        min: 3,
        minMessage: 'First name must be at least {{ limit }} characters long',
        max: 255,
        maxMessage: 'First name cannot be longer than {{ limit }} characters'
    )]
    protected ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Last name cannot be blank')]
    #[Assert\Length(
        min: 3,
        minMessage: 'Last name must be at least {{ limit }} characters long',
        max: 255,
        maxMessage: 'Last name cannot be longer than {{ limit }} characters'
    )]
    protected ?string $lastname = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Birthdate cannot be blank')]
    #[Assert\LessThanOrEqual(
        value: '-13 years',
        message: 'You must be at least 13 years old to register.'
    )]
    #[Assert\GreaterThanOrEqual(
        value: '-120 years',
        message: 'Birthdate cannot be more than 120 years ago.'
    )]
    protected ?\DateTimeImmutable $birthdate = null;

    #[ORM\Column]
    protected bool $isVerified = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFullname(): string
    {
        return ($this->lastname . ' ' . $this->firstname);
    }

    public function getBirthdate(): ?\DateTimeImmutable
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeImmutable $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
