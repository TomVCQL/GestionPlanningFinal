<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'idPlanning', targetEntity: periode::class)]
    private Collection $idPlanning;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebutPlanning = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFinPlanning = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $dureePlanning = null;

    public function __construct()
    {
        $this->idPlanning = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, periode>
     */
    public function getIdPlanning(): Collection
    {
        return $this->idPlanning;
    }

    public function addIdPlanning(periode $idPlanning): self
    {
        if (!$this->idPlanning->contains($idPlanning)) {
            $this->idPlanning->add($idPlanning);
            $idPlanning->setIdPlanning($this);
        }

        return $this;
    }

    public function removeIdPlanning(periode $idPlanning): self
    {
        if ($this->idPlanning->removeElement($idPlanning)) {
            // set the owning side to null (unless already changed)
            if ($idPlanning->getIdPlanning() === $this) {
                $idPlanning->setIdPlanning(null);
            }
        }

        return $this;
    }

    public function getDateDebutPlanning(): ?\DateTimeInterface
    {
        return $this->dateDebutPlanning;
    }

    public function setDateDebutPlanning(\DateTimeInterface $dateDebutPlanning): self
    {
        $this->dateDebutPlanning = $dateDebutPlanning;

        return $this;
    }

    public function getDateFinPlanning(): ?\DateTimeInterface
    {
        return $this->dateFinPlanning;
    }

    public function setDateFinPlanning(\DateTimeInterface $dateFinPlanning): self
    {
        $this->dateFinPlanning = $dateFinPlanning;

        return $this;
    }

    public function getDureePlanning(): ?\DateTimeInterface
    {
        return $this->dureePlanning;
    }

    public function setDureePlanning(\DateTimeInterface $dureePlanning): self
    {
        $this->dureePlanning = $dureePlanning;

        return $this;
    }
}
