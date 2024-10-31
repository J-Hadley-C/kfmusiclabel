<?php

namespace App\Entity;

use App\Repository\CollaborationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity(repositoryClass: CollaborationRepository::class)]
class Collaboration
{
    // Identifiant unique pour chaque instance de Collaboration
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Titre de la collaboration (ex. : projet musical)
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    // Statut de la collaboration, tel que 'en attente', 'accepté', 'rejeté'
    #[ORM\Column(length: 50)]
    private ?string $status = null;

    // Description de la collaboration pour expliquer les détails du projet
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    // Date de création de la collaboration
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Utilisateur qui a initié la collaboration (relation ManyToOne vers User)
     * Un utilisateur peut initier plusieurs collaborations, mais chaque collaboration n'a qu'un initiateur.
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'collaborations')]
    private ?User $initiatedAt = null;

    /**
     * Liste des utilisateurs participant à la collaboration (relation ManyToMany vers User)
     * Plusieurs utilisateurs peuvent participer à une collaboration, et chaque utilisateur peut être dans plusieurs collaborations.
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'participation')]
    private Collection $participation;

    // Constructeur pour initialiser la collection de participants
    public function __construct()
    {
        $this->participation = new ArrayCollection();
    }

    // Getter pour obtenir l'identifiant de la collaboration
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour obtenir le titre de la collaboration
    public function getTitle(): ?string
    {
        return $this->title;
    }

    // Setter pour définir le titre de la collaboration
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    // Getter pour obtenir le statut de la collaboration
    public function getStatus(): ?string
    {
        return $this->status;
    }

    // Setter pour définir le statut de la collaboration
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    // Getter pour obtenir la description de la collaboration
    public function getDescription(): ?string
    {
        return $this->description;
    }

    // Setter pour définir la description de la collaboration
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    // Getter pour obtenir la date de création de la collaboration
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    // Setter pour définir la date de création de la collaboration
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // Getter pour obtenir l'utilisateur qui a initié la collaboration
    public function getInitiatedAt(): ?User
    {
        return $this->initiatedAt;
    }

    // Setter pour définir l'utilisateur qui initie la collaboration
    public function setInitiatedAt(?User $initiatedAt): self
    {
        $this->initiatedAt = $initiatedAt;
        return $this;
    }

    /**
     * Récupère la liste des participants à la collaboration
     * @return Collection<int, User> - Collection d'utilisateurs participant à la collaboration
     */
    public function getParticipation(): Collection
    {
        return $this->participation;
    }

    // Méthode pour ajouter un participant à la collaboration
    public function addParticipation(User $user): self
    {
        // Ajoute l'utilisateur à la liste s'il n'est pas déjà présent
        if (!$this->participation->contains($user)) {
            $this->participation->add($user);
            $user->addParticipation($this); // Mise à jour réciproque dans User
        }
        return $this;
    }

    // Méthode pour supprimer un participant de la collaboration
    public function removeParticipation(User $user): self
    {
        // Retire l'utilisateur de la liste des participants
        if ($this->participation->removeElement($user)) {
            $user->removeParticipation($this); // Mise à jour réciproque dans User
        }
        return $this;
    }
}
