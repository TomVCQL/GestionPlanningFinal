<?php

namespace App\Entity;

use App\Repository\IntervenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IntervenantRepository::class)]
class Intervenant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Matiere::class)]
    private Collection $idInter;

    #[ORM\Column(length: 255)]
    private ?string $nomInter = null;

    #[ORM\Column(length: 255)]
    private ?string $prenomInter = null;

    #[ORM\Column]
    private ?float $nbrHeureTravaillee = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudoInter = null;

    #[ORM\Column(length: 255)]
    private ?string $mdpInter = null;

    public function __construct()
    {
        $this->idInter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, matiere>
     */
    public function getIdInter(): Collection
    {
        return $this->idInter;
    }

    public function addIdInter(matiere $idInter): self
    {
        if (!$this->idInter->contains($idInter)) {
            $this->idInter->add($idInter);
        }

        return $this;
    }

    public function removeIdInter(matiere $idInter): self
    {
        $this->idInter->removeElement($idInter);

        return $this;
    }

    public function getNomInter(): ?string
    {
        return $this->nomInter;
    }

    public function setNomInter(string $nomInter): self
    {
        $this->nomInter = $nomInter;

        return $this;
    }

    public function getPrenomInter(): ?string
    {
        return $this->prenomInter;
    }

    public function setPrenomInter(string $prenomInter): self
    {
        $this->prenomInter = $prenomInter;

        return $this;
    }

    public function getNbrHeureTravaillee(): ?float
    {
        return $this->nbrHeureTravaillee;
    }

    public function setNbrHeureTravaillee(float $nbrHeureTravaillee): self
    {
        $this->nbrHeureTravaillee = $nbrHeureTravaillee;

        return $this;
    }

    public function getPseudoInter(): ?string
    {
        return $this->pseudoInter;
    }

    public function setPseudoInter(string $pseudoInter): self
    {
        $this->pseudoInter = $pseudoInter;

        return $this;
    }

    public function getMdpInter(): ?string
    {
        return $this->mdpInter;
    }

    public function setMdpInter(string $mdpInter): self
    {
        $this->mdpInter = $mdpInter;

        return $this;
    }
}
