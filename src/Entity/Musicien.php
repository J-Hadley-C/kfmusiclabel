<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MusicienRepository;
use App\Entity\User;

#[ORM\Entity(repositoryClass: MusicienRepository::class)]
class Musicien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $instrument = null;

    #[ORM\Column(length: 100)]
    private ?string $genre_musical = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    // Relation OneToOne avec User, pour lier le compte utilisateur au musicien
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'musicien', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    // Relation ManyToOne avec Producteur pour les collaborations
    #[ORM\ManyToOne(targetEntity: Producteur::class, inversedBy: 'musiciens')]
    private ?Producteur $producteur = null;

    // Relation ManyToOne avec Beatmaker pour les collaborations
    #[ORM\ManyToOne(targetEntity: Beatmaker::class, inversedBy: 'musiciens')]
    private ?Beatmaker $beatmaker = null;

    // Relation ManyToMany pour la collaboration avec d'autres musiciens
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'collaborateurs')]
    #[ORM\JoinTable(name: 'musicien_collaborations')]
    private Collection $collaborations;

    public function __construct()
    {
        $this->collaborations = new ArrayCollection();
    }

    // Getters et Setters pour chaque attribut

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

    public function getInstrument(): ?string
    {
        return $this->instrument;
    }

    public function setInstrument(string $instrument): self
    {
        $this->instrument = $instrument;
        return $this;
    }

    public function getGenreMusical(): ?string
    {
        return $this->genre_musical;
    }

    public function setGenreMusical(string $genre_musical): self
    {
        $this->genre_musical = $genre_musical;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    // Relation avec User
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    // Relation avec Producteur
    public function getProducteur(): ?Producteur
    {
        return $this->producteur;
    }

    public function setProducteur(?Producteur $producteur): self
    {
        $this->producteur = $producteur;
        return $this;
    }

    // Relation avec Beatmaker
    public function getBeatmaker(): ?Beatmaker
    {
        return $this->beatmaker;
    }

    public function setBeatmaker(?Beatmaker $beatmaker): self
    {
        $this->beatmaker = $beatmaker;
        return $this;
    }

    // Méthodes pour la relation ManyToMany avec d'autres musiciens
    public function getCollaborations(): Collection
    {
        return $this->collaborations;
    }

    public function addCollaboration(self $musicien): self
    {
        if (!$this->collaborations->contains($musicien)) {
            $this->collaborations->add($musicien);
        }
        return $this;
    }

    public function removeCollaboration(self $musicien): self
    {
        $this->collaborations->removeElement($musicien);
        return $this;
    }
}
