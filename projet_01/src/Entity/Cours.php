<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $dureeC = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DebutC = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $FinC = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDureeC(): ?\DateTimeInterface
    {
        return $this->dureeC;
    }

    public function setDureeC(\DateTimeInterface $dureeC): self
    {
        $this->dureeC = $dureeC;

        return $this;
    }

    public function getDebutC(): ?\DateTimeInterface
    {
        return $this->DebutC;
    }

    public function setDebutC(\DateTimeInterface $DebutC): self
    {
        $this->DebutC = $DebutC;

        return $this;
    }

    public function getFinC(): ?\DateTimeInterface
    {
        return $this->FinC;
    }

    public function setFinC(\DateTimeInterface $FinC): self
    {
        $this->FinC = $FinC;

        return $this;
    }
}
