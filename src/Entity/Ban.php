<?php

namespace App\Entity;

use App\Entity\User\BaseClientUser;
use App\Entity\User\Moderator;
use App\Entity\Util\AbstractTimestamp;
use App\Repository\BanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BanRepository::class)]
class Ban extends AbstractTimestamp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'ban', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?BaseClientUser $baseClientUser = null;

    #[ORM\ManyToOne(inversedBy: 'bans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Moderator $moderator = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientUser(): ?BaseClientUser
    {
        return $this->baseClientUser;
    }

    public function setClientUser(BaseClientUser $clientUser): static
    {
        $this->baseClientUser = $clientUser;

        return $this;
    }

    public function getModerator(): ?Moderator
    {
        return $this->moderator;
    }

    public function setModerator(?Moderator $moderator): static
    {
        $this->moderator = $moderator;

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
}
