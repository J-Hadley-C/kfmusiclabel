<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MusicRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Artist;
use App\Entity\Beatmaker;
use App\Entity\Style;

#[ORM\Entity(repositoryClass: MusicRepository::class)]
class Music
{
    // Identifiant unique pour chaque musique
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Titre de la musique
    #[ORM\Column(length: 180)]
    private ?string $title = null;

    // Lien vers le fichier audio de la musique (URL ou chemin)
    #[ORM\Column(length: 255)]
    private ?string $link = null;

    // Lien vers la couverture de la musique (URL ou chemin de l'image)
    #[ORM\Column(length: 255)]
    private ?string $cover = null;

    /**
     * Artiste associé à la musique
     * @var Artist
     */
    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'musics')]
    private ?Artist $artist = null;

    /**
     * Collection des styles associés à la musique
     * @var Collection<int, Style>
     */
    #[ORM\ManyToMany(targetEntity: Style::class, inversedBy: 'musics')]
    #[ORM\JoinTable(name: 'music_style')]
    private Collection $styles;

    // Constructeur pour initialiser la collection de styles
    public function __construct()
    {
        $this->styles = new ArrayCollection();
    }

    // Getter pour obtenir l'identifiant de la musique
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour obtenir le titre de la musique
    public function getTitle(): ?string
    {
        return $this->title;
    }

    // Setter pour définir le titre de la musique
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    // Getter pour obtenir le lien de la musique
    public function getLink(): ?string
    {
        return $this->link;
    }

    // Setter pour définir le lien de la musique
    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    // Getter pour obtenir le lien de la couverture de la musique
    public function getCover(): ?string
    {
        return $this->cover;
    }

    // Setter pour définir le lien de la couverture de la musique
    public function setCover(string $cover): static
    {
        $this->cover = $cover;

        return $this;
    }

    // Getter pour obtenir l'artiste associé à la musique
    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    // Setter pour définir l'artiste associé à la musique
    public function setArtist(?Artist $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Getter pour la collection des styles associés à la musique
     * @return Collection<int, Style>
     */
    public function getStyles(): Collection
    {
        return $this->styles;
    }

    /**
     * Méthode pour ajouter un style à la musique
     */
    public function addStyle(Style $style): static
    {
        if (!$this->styles->contains($style)) {
            $this->styles->add($style);
        }

        return $this;
    }

    /**
     * Méthode pour retirer un style de la musique
     */
    public function removeStyle(Style $style): static
    {
        $this->styles->removeElement($style);

        return $this;
    }
}
