<?php

namespace App\Controller;

use App\Entity\Collaboration;
use App\Form\CollaborationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CollaborationController extends AbstractController
{
    // Affiche la liste des collaborations
    #[Route('/collaborations', name: 'collaborations_list')]
    public function list(CollaborationRepository $collaborationRepository): Response
    {
        // Vérifie si l'utilisateur connecté a un des rôles autorisés pour voir la liste des collaborations
        if (!$this->isGranted('ROLE_MUSICIAN') &&
            !$this->isGranted('ROLE_ARTIST') &&
            !$this->isGranted('ROLE_BEATMAKER') &&
            !$this->isGranted('ROLE_PRODUCTEUR') &&
            !$this->isGranted('ROLE_ADMIN')) {
            // Si aucun des rôles n'est présent, une exception d'accès est levée
            throw new AccessDeniedException('Vous n\'avez pas la permission de voir les collaborations.');
        }

        // Récupère toutes les collaborations
        $collaborations = $collaborationRepository->findAll();

        // Rend la vue avec la liste des collaborations
        return $this->render('collaboration/list.html.twig', [
            'collaborations' => $collaborations,
        ]);
    }

    // Crée une nouvelle collaboration
    #[Route('/collaborations/new', name: 'collaborations_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur connecté a un des rôles autorisés pour créer une nouvelle collaboration
        if (!$this->isGranted('ROLE_MUSICIAN') &&
            !$this->isGranted('ROLE_ARTIST') &&
            !$this->isGranted('ROLE_BEATMAKER') &&
            !$this->isGranted('ROLE_PRODUCTEUR') &&
            !$this->isGranted('ROLE_ADMIN')) {
            // Si aucun des rôles n'est présent, une exception d'accès est levée
            throw new AccessDeniedException('Vous n\'avez pas la permission de créer une collaboration.');
        }

        // Création d'une nouvelle instance de Collaboration
        $collaboration = new Collaboration();
        
        // Création du formulaire pour la collaboration
        $form = $this->createForm(CollaborationType::class, $collaboration);
        
        // Traite les données soumises dans la requête
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide, enregistre la collaboration
        if ($form->isSubmitted() && $form->isValid()) {
            // Définit l'utilisateur actuel comme l'initiateur de la collaboration
            $collaboration->setInitiatedAt($this->getUser());
            // Définit la date de création comme la date et l'heure actuelles
            $collaboration->setCreatedAt(new \DateTimeImmutable());

            // Persiste la nouvelle collaboration en base de données
            $entityManager->persist($collaboration);
            $entityManager->flush();

            // Redirige vers la liste des collaborations après l'ajout
            return $this->redirectToRoute('collaborations_list');
        }

        // Rend la vue avec le formulaire de création de collaboration
        return $this->render('collaboration/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Affiche le détail d'une collaboration spécifique
    #[Route('/collaborations/{id}', name: 'collaboration_detail')]
    public function detail(Collaboration $collaboration): Response
    {
        // Vérifie si l'utilisateur connecté a un des rôles autorisés pour voir le détail de la collaboration
        if (!$this->isGranted('ROLE_MUSICIAN') &&
            !$this->isGranted('ROLE_ARTIST') &&
            !$this->isGranted('ROLE_BEATMAKER') &&
            !$this->isGranted('ROLE_PRODUCTEUR') &&
            !$this->isGranted('ROLE_ADMIN')) {
            // Si aucun des rôles n'est présent, une exception d'accès est levée
            throw new AccessDeniedException('Vous n\'avez pas la permission de voir le détail de cette collaboration.');
        }

        // Rend la vue avec le détail de la collaboration sélectionnée
        return $this->render('collaboration/detail.html.twig', [
            'collaboration' => $collaboration,
        ]);
    }
}
