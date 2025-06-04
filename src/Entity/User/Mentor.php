<?php

namespace App\Entity\User;

use App\Entity\AcademicStage;
use App\Entity\Certificate;
use App\Repository\User\MentorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MentorRepository::class)]
class Mentor extends User
{
    /**
     * @var Collection<int, AcademicStage>
     */
    #[ORM\OneToMany(targetEntity: AcademicStage::class, mappedBy: 'mentor', orphanRemoval: true)]
    private Collection $academicStages;

    /**
     * @var Collection<int, Certificate>
     */
    #[ORM\OneToMany(targetEntity: Certificate::class, mappedBy: 'mentor', orphanRemoval: true)]
    private Collection $certificates;

    public function __construct()
    {
        $this->academicStages = new ArrayCollection();
        $this->certificates = new ArrayCollection();
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
}
