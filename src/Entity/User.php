<?php

namespace App\Entity;

use App\Entity\Artist;
use App\Entity\Beatmaker;
use App\Entity\Producteur;
use App\Entity\Collaboration;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;  
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Identifiant unique pour chaque utilisateur
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Adresse email unique de l'utilisateur, utilisée pour l'authentification
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    // Rôles de l'utilisateur, stockés dans un tableau (ex. : ROLE_USER, ROLE_ADMIN)
    #[ORM\Column]
    private array $roles = [];

    // Mot de passe de l'utilisateur, haché pour plus de sécurité
    #[ORM\Column]
    private ?string $password = null;

    // Pseudonyme ou nom d'utilisateur visible par d'autres
    #[ORM\Column(length: 50)]
    private ?string $nickname = null;

    // Chemin ou URL de la photo de profil de l'utilisateur
    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    // Numéro de téléphone de l'utilisateur, facultatif
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phone = null;

    // Association OneToOne avec l'entité Artist
    #[ORM\OneToOne(targetEntity: Artist::class, inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Artist $artist = null;

    // Association OneToOne avec l'entité Beatmaker
    #[ORM\OneToOne(targetEntity: Beatmaker::class, cascade: ['persist', 'remove'])]
    private ?Beatmaker $beatmaker = null;

    // Association OneToOne avec l'entité Producteur
    #[ORM\OneToOne(targetEntity: Producteur::class, cascade: ['persist', 'remove'])]
    private ?Producteur $prod = null;

    /**
     * Collaborations initiées par l'utilisateur, relation OneToMany vers Collaboration
     * (L'utilisateur peut initier plusieurs collaborations)
     * @var Collection<int, Collaboration>
     */
    #[ORM\OneToMany(mappedBy: 'initiatedAt', targetEntity: Collaboration::class)]
    private Collection $collaborations;

    /**
     * Collaborations auxquelles l'utilisateur participe, relation ManyToMany avec Collaboration
     * @var Collection<int, Collaboration>
     */
    #[ORM\ManyToMany(targetEntity: Collaboration::class, inversedBy: 'participation')]
    private Collection $participation;

    // Constructeur : initialise les collections pour éviter les erreurs d'accès null
    public function __construct()
    {
        $this->collaborations = new ArrayCollection();
        $this->participation = new ArrayCollection();
    }

    // Retourne l'identifiant unique de l'utilisateur
    public function getId(): ?int
    {
        return $this->id;
    }

    // Retourne l'email de l'utilisateur (identifiant principal)
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // Définit l'email de l'utilisateur
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    // Identifiant unique pour l'authentification
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // Retourne les rôles de l'utilisateur
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; // Ajoute toujours le rôle utilisateur par défaut
        return array_unique($roles);
    }

    // Définit les rôles de l'utilisateur
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    // Retourne le mot de passe de l'utilisateur
    public function getPassword(): ?string
    {
        return $this->password;
    }

    // Définit le mot de passe de l'utilisateur
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    // Retourne le pseudonyme de l'utilisateur
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    // Définit le pseudonyme de l'utilisateur
    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;
        return $this;
    }

    // Retourne la photo de profil de l'utilisateur
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    // Définit la photo de profil de l'utilisateur
    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    // Retourne le numéro de téléphone de l'utilisateur
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    // Retourne l'entité Artist associée
    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    // Associe une entité Artist à l'utilisateur
    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;
        return $this;
    }

    // Retourne l'entité Beatmaker associée
    public function getBeatmaker(): ?Beatmaker
    {
        return $this->beatmaker;
    }

    // Associe une entité Beatmaker à l'utilisateur
    public function setBeatmaker(?Beatmaker $beatmaker): self
    {
        $this->beatmaker = $beatmaker;
        return $this;
    }

    // Retourne l'entité Producteur associée
    public function getProducteur(): ?Producteur
    {
        return $this->prod;
    }

    // Associe une entité Producteur à l'utilisateur
    public function setProducteur(?Producteur $prod): self
    {
        $this->prod = $prod;
        return $this;
    }

    /**
     * Retourne les collaborations initiées par l'utilisateur
     */
    public function getCollaborations(): Collection
    {
        return $this->collaborations;
    }

    // Ajoute une collaboration à celles initiées par l'utilisateur
    public function addCollaboration(Collaboration $collaboration): self
    {
        if (!$this->collaborations->contains($collaboration)) {
            $this->collaborations->add($collaboration);
            $collaboration->setInitiatedAt($this);
        }
        return $this;
    }

    // Retire une collaboration des collaborations initiées
    public function removeCollaboration(Collaboration $collaboration): self
    {
        if ($this->collaborations->removeElement($collaboration)) {
            if ($collaboration->getInitiatedAt() === $this) {
                $collaboration->setInitiatedAt(null);
            }
        }
        return $this;
    }

    /**
     * Retourne les collaborations auxquelles l'utilisateur participe
     */
    public function getParticipation(): Collection
    {
        return $this->participation;
    }

    // Ajoute une collaboration aux participations de l'utilisateur
    public function addParticipation(Collaboration $collaboration): self
    {
        if (!$this->participation->contains($collaboration)) {
            $this->participation->add($collaboration);
            $collaboration->addParticipation($this); // Mise à jour réciproque
        }
        return $this;
    }

    // Retire une collaboration des participations de l'utilisateur
    public function removeParticipation(Collaboration $collaboration): self
    {
        if ($this->participation->removeElement($collaboration)) {
            $collaboration->removeParticipation($this); // Mise à jour réciproque
        }
        return $this;
    }

    // Efface les données sensibles, si nécessaire (obligatoire pour l'authentification Symfony)
    public function eraseCredentials(): void
    {
        // Laisser vide si aucune donnée sensible n'est stockée
    }
    private bool $isVerified = false; // Par défaut, l'email n'est pas vérifié

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $verificationToken = null;

    // Méthodes getter et setter pour isVerified
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    // Méthodes getter et setter pour verificationToken
    public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    public function setVerificationToken(?string $verificationToken): self
    {
        $this->verificationToken = $verificationToken;

        return $this;
    }
}
