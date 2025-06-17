<?php

namespace App\Entity\Traits;

use Symfony\Component\Uid\Ulid;
use Doctrine\ORM\Mapping as ORM;

trait HasPublicIdTrait
{
    #[ORM\Column(type: 'ulid', unique: true)]
    protected ?Ulid $publicId = null;

    public function getPublicId(): ?Ulid
    {
        return $this->publicId;
    }

    public function getPublicIdSimplify(): ?string
    {
        return $this->publicId?->toBase58();
    }

    #[ORM\PrePersist]
    public function initializePublicId(): void
    {
        if ($this->publicId === null) {
            $this->publicId = new Ulid();
        }
    }
}
