<?php

namespace App\Entity\Users\Mentor;

use App\Validator as AppAssert;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Users\BaseClientUser;
use App\Entity\Enums\MentorRegisterStep;
use App\Entity\Users\Mentor\Certificate;
use App\Entity\Users\Mentor\AcademicStage;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\Users\Mentor\MentorRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MentorRepository::class)]
class Mentor extends BaseClientUser
{


    /**
     * @var Collection<int, Certificate>
     */
    #[ORM\OneToMany(targetEntity: Certificate::class, mappedBy: 'mentor', orphanRemoval: true, cascade: ['persist'])]
    #[Assert\Valid(groups:["mentor:certificates"])]
    private Collection $certificates;

    #[ORM\Column(enumType: MentorRegisterStep::class)]
    private MentorRegisterStep $registerStep = MentorRegisterStep::EDUCATION_STEP;

    /**
     * @var Collection<int, AcademicStage>
     */
    #[ORM\OneToMany(targetEntity: AcademicStage::class, mappedBy: 'mentor', orphanRemoval: true, cascade: ["persist"])]
    #[Assert\Count(
        min: 1,
        minMessage: 'You must specify at least one available day.',
        groups:["mentor:academic-stages"]
    )]
    #[Assert\Valid(
        groups:["mentor:academic-stages"]
        )]
    private Collection $academicStages;

    /**
     * @var Collection<int, Day>
     */
    #[ORM\OneToMany(targetEntity: Day::class, mappedBy: 'mentor', orphanRemoval: true, cascade: ["persist"])]
    #[Assert\Count(
        min: 1,
        minMessage: 'You must specify at least one available day.',
        max: 7,
        maxMessage: 'You must specify at {{ limit }} days maximum.',
        groups: ["mentor:availables-price"]    
    )]
    #[Assert\Valid(
        groups: ["mentor:availables-price"]
    )]
    #[AppAssert\Availables(groups: ["mentor:availables-price"])]
    private Collection $availables;

    #[ORM\Column]
    #[Assert\NotBlank(
        groups: ["mentor:availables-price"]
    )]
    #[Assert\Range(
        min: 1000,
        max: 50000,
        notInRangeMessage: 'The price must be between {{ min }}Fcfa and {{ max }}Fcfa.',
        groups: ["mentor:availables-price"]
    )]
    private ?int $price = null;

    public function __construct()
    {
        $this->certificates = new ArrayCollection();
        $this->academicStages = new ArrayCollection();
        $this->availables = new ArrayCollection();
    }



    /**
     * @return Collection<int, Certificate>
     */
    public function getCertificates(): Collection
    {
        return $this->certificates;
    }

    public function addCertificate(Certificate $certificate): static
    {
        if (!$this->certificates->contains($certificate)) {
            $this->certificates->add($certificate);
            $certificate->setMentor($this);
        }

        return $this;
    }

    public function removeCertificate(Certificate $certificate): static
    {
        if ($this->certificates->removeElement($certificate)) {
            // set the owning side to null (unless already changed)
            if ($certificate->getMentor() === $this) {
                $certificate->setMentor(null);
            }
        }

        return $this;
    }

    public function getRegisterStep(): MentorRegisterStep
    {
        return $this->registerStep;
    }

    public function setRegisterStep(MentorRegisterStep $registerStep): static
    {
        $this->registerStep = $registerStep;

        return $this;
    }

    /**
     * @return Collection<int, AcademicStage>
     */
    public function getAcademicStages(): Collection
    {
        return $this->academicStages;
    }

    public function addAcademicStage(AcademicStage $academicStage): static
    {
        if (!$this->academicStages->contains($academicStage)) {
            $this->academicStages->add($academicStage);
            $academicStage->setMentor($this);
        }

        return $this;
    }

    public function removeAcademicStage(AcademicStage $academicStage): static
    {
        if ($this->academicStages->removeElement($academicStage)) {
            // set the owning side to null (unless already changed)
            if ($academicStage->getMentor() === $this) {
                $academicStage->setMentor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Day>
     */
    public function getAvailables(): Collection
    {
        return $this->availables;
    }

    public function addAvailable(Day $available): static
    {
        if (!$this->availables->contains($available)) {
            $this->availables->add($available);
            $available->setMentor($this);
        }

        return $this;
    }

    public function removeAvailable(Day $available): static
    {
        if ($this->availables->removeElement($available)) {
            // set the owning side to null (unless already changed)
            if ($available->getMentor() === $this) {
                $available->setMentor(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }
}
