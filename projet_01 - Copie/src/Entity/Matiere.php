<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Cours::class)]
    private Collection $idMat;

    #[ORM\Column(length: 255)]
    private ?string $nomMat = null;

    #[ORM\Column]
    private ?float $heureTotalMat = null;

    #[ORM\Column]
    private ?float $heureEnseigne = null;

    public function __construct()
    {
        $this->idMat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, cours>
     */
    public function getIdMat(): Collection
    {
        return $this->idMat;
    }

    public function addIdMat(cours $idMat): self
    {
        if (!$this->idMat->contains($idMat)) {
            $this->idMat->add($idMat);
        }

        return $this;
    }

    public function removeIdMat(cours $idMat): self
    {
        $this->idMat->removeElement($idMat);

        return $this;
    }

    public function getNomMat(): ?string
    {
        return $this->nomMat;
    }

    public function setNomMat(string $nomMat): self
    {
        $this->nomMat = $nomMat;

        return $this;
    }

    public function getHeureTotalMat(): ?float
    {
        return $this->heureTotalMat;
    }

    public function setHeureTotalMat(float $heureTotalMat): self
    {
        $this->heureTotalMat = $heureTotalMat;

        return $this;
    }

    public function getHeureEnseigne(): ?float
    {
        return $this->heureEnseigne;
    }

    public function setHeureEnseigne(float $heureEnseigne): self
    {
        $this->heureEnseigne = $heureEnseigne;

        return $this;
    }
}
