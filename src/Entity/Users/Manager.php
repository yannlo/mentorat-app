<?php

namespace App\Entity\Users;

use App\Repository\Users\ManagerRepository;
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
