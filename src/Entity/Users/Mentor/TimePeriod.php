<?php

namespace App\Entity\Users\Mentor;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utils\AbstractTimestamp;
use App\Repository\Users\Mentor\TimePeriodRepository;
use \Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TimePeriodRepository::class)]
class TimePeriod extends AbstractTimestamp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(
        groups: ["mentor:availables-price"]
        )]
    #[Assert\Range(
        groups: ["mentor:availables-price"],
        min: 7,
        max: 23,
        notInRangeMessage: "L'heure doit etre comprise en {{ min }}h et {{ max }}h.",

    )]
    private ?int $start = null;

    #[ORM\Column]
    #[Assert\NotBlank(groups: ["mentor:availables-price"])]
    #[Assert\Range(
        groups: ["mentor:availables-price"],
        min: 7,
        max: 23,
        notInRangeMessage: "L'heure doit etre comprise en {{ min }}h et {{ max }}h.",
    )]
    #[Assert\GreaterThan(groups: ["mentor:availables-price"], propertyPath: "start", message: "L'heure de fin doit etre suprérieur a l'heure de départ. ({{ compared_value }}h)")]
    private ?int $end = null;

    #[ORM\ManyToOne(inversedBy: 'periods')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Day $day = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?int
    {
        return $this->start;
    }

    public function setStart(int $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?int
    {
        return $this->end;
    }

    public function setEnd(int $end): static
    {
        $this->end = $end;

        return $this;
    }

    public function getDay(): ?Day
    {
        return $this->day;
    }

    public function setDay(?Day $day): static
    {
        $this->day = $day;

        return $this;
    }
}
