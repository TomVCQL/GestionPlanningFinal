<?php

namespace App\Entity;

use App\Repository\PeriodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeriodeRepository::class)]
class Periode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: cours::class)]
    private Collection $idPeriode;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebutPeriode = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFinPeriode = null;

    #[ORM\Column]
    private ?int $typePeriode = null;

    #[ORM\ManyToOne(inversedBy: 'idPlanning')]
    private ?Planning $idPlanning = null;

    public function __construct()
    {
        $this->idPeriode = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, cours>
     */
    public function getIdPeriode(): Collection
    {
        return $this->idPeriode;
    }

    public function addIdPeriode(cours $idPeriode): self
    {
        if (!$this->idPeriode->contains($idPeriode)) {
            $this->idPeriode->add($idPeriode);
        }

        return $this;
    }

    public function removeIdPeriode(cours $idPeriode): self
    {
        $this->idPeriode->removeElement($idPeriode);

        return $this;
    }

    public function getDateDebutPeriode(): ?\DateTimeInterface
    {
        return $this->dateDebutPeriode;
    }

    public function setDateDebutPeriode(\DateTimeInterface $dateDebutPeriode): self
    {
        $this->dateDebutPeriode = $dateDebutPeriode;

        return $this;
    }

    public function getDateFinPeriode(): ?\DateTimeInterface
    {
        return $this->dateFinPeriode;
    }

    public function setDateFinPeriode(\DateTimeInterface $dateFinPeriode): self
    {
        $this->dateFinPeriode = $dateFinPeriode;

        return $this;
    }

    public function getTypePeriode(): ?int
    {
        return $this->typePeriode;
    }

    public function setTypePeriode(int $typePeriode): self
    {
        $this->typePeriode = $typePeriode;

        return $this;
    }

    public function getIdPlanning(): ?Planning
    {
        return $this->idPlanning;
    }

    public function setIdPlanning(?Planning $idPlanning): self
    {
        $this->idPlanning = $idPlanning;

        return $this;
    }
}
