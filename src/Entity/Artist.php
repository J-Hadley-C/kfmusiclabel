<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User;
use App\Entity\Music;
use App\Entity\Follow;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'artist', targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $bio = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $age = null;

    #[ORM\Column(length: 50)]
    private ?string $city = null;

    #[ORM\OneToMany(targetEntity: Music::class, mappedBy: 'artist')]
    private Collection $musics;

    #[ORM\OneToMany(targetEntity: Follow::class, mappedBy: 'artist')]
    private Collection $followers;

    public function __construct()
    {
        $this->musics = new ArrayCollection();
        $this->followers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): static
    {
        $this->bio = $bio;
        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?string $age): static
    {
        $this->age = $age;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function getMusics(): Collection
    {
        return $this->musics;
    }

    public function getFollowers(): Collection
    {
        return $this->followers;
    }
}
