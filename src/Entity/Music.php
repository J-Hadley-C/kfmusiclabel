<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MusicRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Artist;
use App\Entity\Style;
use DateTime;

#[ORM\Entity(repositoryClass: MusicRepository::class)]
class Music
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $filePath = null;  // Chemin du fichier audio

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cover = null;

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $likesCount = 0;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $createdAt;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'musics')]
    private ?Artist $artist = null;

    #[ORM\ManyToMany(targetEntity: Style::class, inversedBy: 'musics')]
    #[ORM\JoinTable(name: 'music_style')]
    private Collection $styles;

    public function __construct()
    {
        $this->styles = new ArrayCollection();
        $this->createdAt = new \DateTime(); // Date de création définie automatiquement
    }

    // Getters et setters pour les nouvelles propriétés

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): self
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): static
    {
        $this->cover = $cover;
        return $this;
    }

    public function getLikesCount(): int
    {
        return $this->likesCount;
    }

    public function setLikesCount(int $likesCount): static
    {
        $this->likesCount = $likesCount;
        return $this;
    }

    public function incrementLikes(): void
    {
        $this->likesCount++;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): static
    {
        $this->artist = $artist;
        return $this;
    }

    /**
     * Gestion de la relation avec l'entité Style
     */
    public function getStyles(): Collection
    {
        return $this->styles;
    }

    public function addStyle(Style $style): static
    {
        if (!$this->styles->contains($style)) {
            $this->styles->add($style);
            $style->addMusic($this); // Synchronise avec Style
        }

        return $this;
    }

    public function removeStyle(Style $style): static
    {
        if ($this->styles->removeElement($style)) {
            $style->removeMusic($this); // Synchronise avec Style
        }

        return $this;
    }
}
