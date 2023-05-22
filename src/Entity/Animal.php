<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?int $tailleMoyenne = null;

    #[ORM\ManyToOne]
    private ?Country $pays = null;

    #[ORM\Column(nullable: true)]
    private ?int $dureeDeVieMoyenne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $artMartial = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroTelephone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTailleMoyenne(): ?int
    {
        return $this->tailleMoyenne;
    }

    public function setTailleMoyenne(?int $tailleMoyenne): self
    {
        $this->tailleMoyenne = $tailleMoyenne;

        return $this;
    }

    public function getPays(): ?Country
    {
        return $this->pays;
    }

    public function setPays(?Country $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getDureeDeVieMoyenne(): ?int
    {
        return $this->dureeDeVieMoyenne;
    }

    public function setDureeDeVieMoyenne(?int $dureeDeVieMoyenne): self
    {
        $this->dureeDeVieMoyenne = $dureeDeVieMoyenne;

        return $this;
    }

    public function getArtMartial(): ?string
    {
        return $this->artMartial;
    }

    public function setArtMartial(?string $artMartial): self
    {
        $this->artMartial = $artMartial;

        return $this;
    }

    public function getNumeroTelephone(): ?string
    {
        return $this->numeroTelephone;
    }

    public function setNumeroTelephone(?string $numeroTelephone): self
    {
        $this->numeroTelephone = $numeroTelephone;

        return $this;
    }
}
