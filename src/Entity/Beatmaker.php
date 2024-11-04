<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BeatmakerRepository::class)]
class Beatmaker
{
    // Identifiant unique pour chaque beatmaker
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Biographie du beatmaker
    #[ORM\Column(type: Types::TEXT)]
    private ?string $bio = null;

    // Ville de résidence du beatmaker
    #[ORM\Column(length: 50)]
    private ?string $city = null;

    // Photo du beatmaker (chemin ou URL de l'image)
    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    /**
     * Collection des musiques produites par le beatmaker
     * @var Collection<int, Music>
     */
    #[ORM\OneToMany(targetEntity: Music::class, mappedBy: 'beatmaker')]
    private Collection $producedMusics;

    // Constructeur pour initialiser la collection de musiques produites
    public function __construct()
    {
        $this->producedMusics = new ArrayCollection();
    }

    // Getter pour l'identifiant du beatmaker
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour la biographie du beatmaker
    public function getBio(): ?string
    {
        return $this->bio;
    }

    // Setter pour la biographie du beatmaker
    public function setBio(string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    // Getter pour la ville de résidence du beatmaker
    public function getCity(): ?string
    {
        return $this->city;
    }

    // Setter pour la ville de résidence du beatmaker
    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    // Getter pour la photo du beatmaker
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    // Setter pour la photo du beatmaker
    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Getter pour la collection des musiques produites par le beatmaker
     * @return Collection<int, Music>
     */
    public function getProducedMusics(): Collection
    {
        return $this->producedMusics;
    }

    /**
     * Méthode pour ajouter une musique produite par le beatmaker
     */
    public function addProducedMusic(Music $music): static
    {
        if (!$this->producedMusics->contains($music)) {
            $this->producedMusics->add($music);
            $music->setBeatmaker($this);
        }

        return $this;
    }

    /**
     * Méthode pour retirer une musique produite par le beatmaker
     */
    public function removeProducedMusic(Music $music): static
    {
        if ($this->producedMusics->removeElement($music)) {
            if ($music->getBeatmaker() === $this) {
                $music->setBeatmaker(null);
            }
        }

        return $this;
    }
}
