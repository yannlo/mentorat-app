<?php

namespace App\Entity\Users\Mentor;

use App\Validator as AppAssert;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Enums\Day as DayList;

use App\Entity\Utils\AbstractTimestamp;
use Doctrine\Common\Collections\Collection;
use App\Repository\Users\Mentor\DayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DayRepository::class)]
class Day extends AbstractTimestamp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: DayList::class)]
    #[Assert\NotBlank]
    private ?DayList $name = null;

    /**
     * @var Collection<int, TimePeriod>
     */
    #[ORM\OneToMany(targetEntity: TimePeriod::class, mappedBy: 'day', orphanRemoval: true, cascade:["persist"])]
    #[AppAssert\Periods]
    private Collection $periods;

    #[ORM\ManyToOne(inversedBy: 'availables')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mentor $mentor = null;

    public function __construct()
    {
        $this->periods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?DayList
    {
        return $this->name;
    }

    public function setName(DayList $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, TimePeriod>
     */
    public function getPeriods(): Collection
    {
        return $this->periods;
    }

    public function addPeriod(TimePeriod $period): static
    {
        if (!$this->periods->contains($period)) {
            $this->periods->add($period);
            $period->setDay($this);
        }

        return $this;
    }

    public function removePeriod(TimePeriod $period): static
    {
        if ($this->periods->removeElement($period)) {
            // set the owning side to null (unless already changed)
            if ($period->getDay() === $this) {
                $period->setDay(null);
            }
        }

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
