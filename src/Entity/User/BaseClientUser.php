<?php

namespace App\Entity\User;

use App\Entity\Ban;
use App\Repository\User\BaseClientUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BaseClientUserRepository::class)]
#[ORM\InheritanceType(value: 'JOINED')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap([
    'student' => Student::class,
    'mentor' => Mentor::class,
])]
abstract class BaseClientUser extends User
{
    #[ORM\OneToOne(mappedBy: 'baseClientUser', cascade: ['persist', 'remove'])]
    private ?Ban $ban = null;

    public function getBan(): ?Ban
    {
        return $this->ban;
    }

    public function setBan(Ban $ban): static
    {
        // set the owning side of the relation if necessary
        if ($ban->getClientUser() !== $this) {
            $ban->setClientUser($this);
        }

        $this->ban = $ban;

        return $this;
    }
}
