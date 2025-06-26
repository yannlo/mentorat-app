<?php

namespace App\Entity\Users;

use App\Entity\Ban;
use App\Entity\Traits\HasPublicIdTrait;
use App\Entity\Users\User;
use App\Entity\Users\Mentor\Mentor;
use App\Entity\Users\Student;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Users\BaseClientUserRepository;

#[ORM\Entity(repositoryClass: BaseClientUserRepository::class)]
#[ORM\InheritanceType(value: 'JOINED')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap([
    'student' => Student::class,
    'mentor' => Mentor::class,
])]
#[ORM\HasLifecycleCallbacks]
abstract class BaseClientUser extends User
{
    use HasPublicIdTrait;

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
