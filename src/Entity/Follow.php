<?php

namespace App\Entity;

use App\Repository\FollowRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Artist;

#[ORM\Entity(repositoryClass: FollowRepository::class)]
class Follow
{
    // Identifiant unique pour chaque instance de Follow
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Date à laquelle le suivi a été créé, initialisée automatiquement
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    /**
     * Artiste qui est suivi
     * Relation ManyToOne vers l'entité Artist
     */
    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'followers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artist $artist = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable(); // Définit la date de création à la date actuelle
    }

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

    // Getter pour obtenir l'artiste qui est suivi
    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    // Setter pour définir l'artiste qui est suivi
    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;
        return $this;
    }
}
