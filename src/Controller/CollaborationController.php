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

class CollaborationController extends AbstractController
{
    // Route pour afficher la liste des collaborations
    #[Route('/collaborations', name: 'collaborations_list')]
    public function list(CollaborationRepository $collaborationRepository): Response
    {
        // Vérifie que l'utilisateur a le rôle d'artiste pour accéder à cette page
        $this->denyAccessUnlessGranted('ROLE_ARTIST');

        // Récupère toutes les collaborations existantes
        $collaborations = $collaborationRepository->findAll();

        return $this->render('collaboration/list.html.twig', [
            'collaborations' => $collaborations,
        ]);
    }

    // Route pour créer une nouvelle collaboration
    #[Route('/collaborations/new', name: 'collaborations_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie que l'utilisateur a le rôle d'artiste pour créer une collaboration
        $this->denyAccessUnlessGranted('ROLE_ARTIST');

        // Crée une nouvelle instance de Collaboration
        $collaboration = new Collaboration();
        $form = $this->createForm(CollaborationType::class, $collaboration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associe l'utilisateur actuel (artiste) en tant qu'initiateur de la collaboration
            $artist = $this->getUser()->getArtist();
            if (!$artist) {
                throw $this->createNotFoundException('Artiste non trouvé pour cet utilisateur.');
            }
            $collaboration->setInitiator($artist);
            $collaboration->setCreatedAt(new \DateTimeImmutable()); // Enregistre la date actuelle de création

            $entityManager->persist($collaboration);
            $entityManager->flush();

            // Redirige vers la liste des collaborations après la création
            return $this->redirectToRoute('collaborations_list');
        }

        // Affiche le formulaire de création de collaboration
        return $this->render('collaboration/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route pour afficher les détails d'une collaboration
    #[Route('/collaborations/{id}', name: 'collaboration_detail')]
    public function detail(Collaboration $collaboration): Response
    {
        // Vérifie que l'utilisateur a le rôle d'artiste pour voir les détails de la collaboration
        $this->denyAccessUnlessGranted('ROLE_ARTIST');

        // Affiche les détails de la collaboration
        return $this->render('collaboration/detail.html.twig', [
            'collaboration' => $collaboration,
        ]);
    }
}
