<?php

namespace App\Entity;

use App\Repository\AlternantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlternantRepository::class)]
class Alternant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idAlter = null;

    #[ORM\Column(length: 255)]
    private ?string $nomAlter = null;

    #[ORM\Column(length: 255)]
    private ?string $prenomAlter = null;

    #[ORM\Column(length: 255)]
    private ?string $mailAlter = null;

    #[ORM\Column]
    private ?int $idSpe = null;

    #[ORM\Column(length: 3)]
    private ?string $pseudoAlter = null;


    #[ORM\Column(length: 255)]
    private ?string $mdpAlter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAlter(): ?int
    {
        return $this->idAlter;
    }

    public function setIdAlter(int $idAlter): self
    {
        $this->idAlter = $idAlter;

        return $this;
    }

    public function getNomAlter(): ?string
    {
        return $this->nomAlter;
    }

    public function setNomAlter(string $nomAlter): self
    {
        $this->nomAlter = $nomAlter;

        return $this;
    }

    public function getPrenomAlter(): ?string
    {
        return $this->prenomAlter;
    }

    public function setPrenomAlter(string $prenomAlter): self
    {
        $this->prenomAlter = $prenomAlter;

        return $this;
    }

    public function getMailAlter(): ?string
    {
        return $this->mailAlter;
    }

    public function setMailAlter(string $mailAlter): self
    {
        $this->mailAlter = $mailAlter;

        return $this;
    }

    public function getIdSpe(): ?int
    {
        return $this->idSpe;
    }

    public function setIdSpe(int $idSpe): self
    {
        $this->idSpe = $idSpe;

        return $this;
    }

    public function getPseudoAlter(): ?string
    {
        return $this->pseudoAlter;
    }

    public function setPseudoAlter(string $pseudoAlter): self
    {
        $this->pseudoAlter = $pseudoAlter;

        return $this;
    }

    public function getMdpAlter(): ?string
    {
        return $this->mdpAlter;
    }

    public function setMdpAlter(string $mdpAlter): self
    {
        $this->mdpAlter = $mdpAlter;

        return $this;
    }
}
