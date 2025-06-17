<?php

namespace App\Entity\Users;

use App\Entity\Ban;
use App\Repository\User\ModeratorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModeratorRepository::class)]
class Moderator extends User
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Manager $createdBy = null;

    /**
     * @var Collection<int, Ban>
     */
    #[ORM\OneToMany(targetEntity: Ban::class, mappedBy: 'moderator')]
    private Collection $bans;

    public function __construct()
    {
        $this->bans = new ArrayCollection();
    }


    public function getCreatedBy(): ?Manager
    {
        return $this->createdBy;
    }

    public function setCreatedBy(Manager $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, Ban>
     */
    public function getBans(): Collection
    {
        return $this->bans;
    }

    public function addBan(Ban $ban): static
    {
        if (!$this->bans->contains($ban)) {
            $this->bans->add($ban);
            $ban->setModerator($this);
        }

        return $this;
    }

    public function removeBan(Ban $ban): static
    {
        if ($this->bans->removeElement($ban)) {
            // set the owning side to null (unless already changed)
            if ($ban->getModerator() === $this) {
                $ban->setModerator(null);
            }
        }

        return $this;
    }
}
