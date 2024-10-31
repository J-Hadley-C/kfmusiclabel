<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProducteurRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Follow;

#[ORM\Entity(repositoryClass: ProducteurRepository::class)]
class Producteur
{
    // Identifiant unique pour chaque producteur
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Numéro SIRET du producteur (peut être nullable)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siret = null;

    // Biographie du producteur
    #[ORM\Column(type: Types::TEXT)]
    private ?string $bio = null;

    // Adresse du producteur
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    // Ville de résidence du producteur
    #[ORM\Column(length: 50)]
    private ?string $city = null;

    /**
     * Collection des artistes suivis par le producteur
     * @var Collection<int, Follow>
     */
    #[ORM\OneToMany(targetEntity: Follow::class, mappedBy: 'prod')]
    private Collection $follows;

    // Constructeur pour initialiser la collection de suivis
    public function __construct()
    {
        $this->follows = new ArrayCollection();
    }

    // Getter pour obtenir l'identifiant du producteur
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour obtenir le numéro SIRET du producteur
    public function getSiret(): ?string
    {
        return $this->siret;
    }

    // Setter pour définir le numéro SIRET du producteur
    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    // Getter pour obtenir la biographie du producteur
    public function getBio(): ?string
    {
        return $this->bio;
    }

    // Setter pour définir la biographie du producteur
    public function setBio(string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    // Getter pour obtenir l'adresse du producteur
    public function getAddress(): ?string
    {
        return $this->address;
    }

    // Setter pour définir l'adresse du producteur
    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    // Getter pour obtenir la ville de résidence du producteur
    public function getCity(): ?string
    {
        return $this->city;
    }

    // Setter pour définir la ville de résidence du producteur
    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Getter pour la collection des suivis du producteur
     * @return Collection<int, Follow>
     */
    public function getFollows(): Collection
    {
        return $this->follows;
    }

    /**
     * Méthode pour ajouter un suivi à la collection
     */
    public function addFollow(Follow $follow): static
    {
        if (!$this->follows->contains($follow)) {
            $this->follows->add($follow);
            $follow->setProd($this);
        }

        return $this;
    }

    /**
     * Méthode pour retirer un suivi de la collection
     */
    public function removeFollow(Follow $follow): static
    {
        if ($this->follows->removeElement($follow)) {
            if ($follow->getProd() === $this) {
                $follow->setProd(null);
            }
        }

        return $this;
    }
}
