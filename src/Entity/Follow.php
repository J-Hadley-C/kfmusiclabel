<?php

namespace App\Entity;

use App\Repository\FollowRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Producteur;
use App\Entity\Artist;

#[ORM\Entity(repositoryClass: FollowRepository::class)]
class Follow
{
    // Identifiant unique pour chaque instance de Follow
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Date à laquelle le suivi a été créé
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Producteur qui suit l'artiste
     * @var Producteur
     */
    #[ORM\ManyToOne(targetEntity: Producteur::class, inversedBy: 'follows')]
    private ?Producteur $prod = null;

    /**
     * Artiste qui est suivi par le producteur
     * @var Artist
     */
    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'followers')]
    private ?Artist $artist = null;

    // Getter pour obtenir l'identifiant du suivi
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour obtenir la date de création du suivi
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    // Setter pour définir la date de création du suivi
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // Getter pour obtenir le producteur qui suit l'artiste
    public function getProd(): ?Producteur
    {
        return $this->prod;
    }

    // Setter pour définir le producteur qui suit l'artiste
    public function setProd(?Producteur $prod): static
    {
        $this->prod = $prod;

        return $this;
    }

    // Getter pour obtenir l'artiste qui est suivi par le producteur
    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    // Setter pour définir l'artiste qui est suivi par le producteur
    public function setArtist(?Artist $artist): static
    {
        $this->artist = $artist;

        return $this;
    }
}
