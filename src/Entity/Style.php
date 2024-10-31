<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StyleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Music;

#[ORM\Entity(repositoryClass: StyleRepository::class)]
class Style
{
    // Identifiant unique pour chaque style
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Nom du style (ex: Jazz, Hip-hop, Pop, etc.)
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    // Image associée au style (URL ou chemin d'image)
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * Collection des musiques associées au style
     * @var Collection<int, Music>
     */
    #[ORM\ManyToMany(targetEntity: Music::class, mappedBy: 'styles')]
    private Collection $musics;

    // Constructeur pour initialiser la collection de musiques
    public function __construct()
    {
        $this->musics = new ArrayCollection();
    }

    // Getter pour obtenir l'identifiant du style
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour obtenir le nom du style
    public function getName(): ?string
    {
        return $this->name;
    }

    // Setter pour définir le nom du style
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    // Getter pour obtenir l'image associée au style
    public function getImage(): ?string
    {
        return $this->image;
    }

    // Setter pour définir l'image associée au style
    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Getter pour la collection des musiques associées au style
     * @return Collection<int, Music>
     */
    public function getMusics(): Collection
    {
        return $this->musics;
    }

    /**
     * Méthode pour ajouter une musique au style
     */
    public function addMusic(Music $music): static
    {
        if (!$this->musics->contains($music)) {
            $this->musics->add($music);
            $music->addStyle($this); // Synchronise avec l'entité Music
        }

        return $this;
    }

    /**
     * Méthode pour retirer une musique du style
     */
    public function removeMusic(Music $music): static
    {
        if ($this->musics->removeElement($music)) {
            $music->removeStyle($this); // Synchronise avec l'entité Music
        }

        return $this;
    }
}
