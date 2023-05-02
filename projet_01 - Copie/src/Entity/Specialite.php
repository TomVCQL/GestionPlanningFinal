<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idSpe = null;

    #[ORM\Column(length: 255)]
    private ?string $nomSpe = null;

    #[ORM\Column]
    private ?int $idMat = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomSpe(): ?string
    {
        return $this->nomSpe;
    }

    public function setNomSpe(string $nomSpe): self
    {
        $this->nomSpe = $nomSpe;

        return $this;
    }

    public function getIdMat(): ?int
    {
        return $this->idMat;
    }

    public function setIdMat(int $idMat): self
    {
        $this->idMat = $idMat;

        return $this;
    }
}
