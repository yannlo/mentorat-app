<?php

namespace App\Entity\User;

use App\Repository\User\ManagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ManagerRepository::class)]

class Manager extends User
{
    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $createdBy = null;

    public function getCreatedBy(): ?self
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?self $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
