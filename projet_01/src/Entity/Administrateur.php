<?php

namespace App\Entity;

use App\Repository\AdministrateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdministrateurRepository::class)]
class Administrateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idAdmin = null;

    #[ORM\Column(length: 255)]
    private ?string $nomAdmin = null;

    #[ORM\Column(length: 255)]
    private ?string $prenomAdmin = null;

    #[ORM\Column(length: 255)]
    private ?string $mailAdmin = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudoAdmin = null;

    #[ORM\Column(length: 255)]
    private ?string $mdpAdmin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAdmin(): ?int
    {
        return $this->idAdmin;
    }

    public function setIdAdmin(int $idAdmin): self
    {
        $this->idAdmin = $idAdmin;

        return $this;
    }

    public function getNomAdmin(): ?string
    {
        return $this->nomAdmin;
    }

    public function setNomAdmin(string $nomAdmin): self
    {
        $this->nomAdmin = $nomAdmin;

        return $this;
    }

    public function getPrenomAdmin(): ?string
    {
        return $this->prenomAdmin;
    }

    public function setPrenomAdmin(string $prenomAdmin): self
    {
        $this->prenomAdmin = $prenomAdmin;

        return $this;
    }

    public function getMailAdmin(): ?string
    {
        return $this->mailAdmin;
    }

    public function setMailAdmin(string $mailAdmin): self
    {
        $this->mailAdmin = $mailAdmin;

        return $this;
    }

    public function getPseudoAdmin(): ?string
    {
        return $this->pseudoAdmin;
    }

    public function setPseudoAdmin(string $pseudoAdmin): self
    {
        $this->pseudoAdmin = $pseudoAdmin;

        return $this;
    }

    public function getMdpAdmin(): ?string
    {
        return $this->mdpAdmin;
    }

    public function setMdpAdmin(string $mdpAdmin): self
    {
        $this->mdpAdmin = $mdpAdmin;

        return $this;
    }
}
