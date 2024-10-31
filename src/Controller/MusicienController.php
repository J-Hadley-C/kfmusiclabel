<?php

namespace App\Controller;

use App\Entity\Musicien;
use App\Form\MusicienType;
use App\Repository\MusicienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MusicienController extends AbstractController
{
    // Route pour afficher la liste des musiciens
    #[Route('/musiciens', name: 'musicien_index')]
    public function index(MusicienRepository $musicienRepository): Response
    {
        // Récupère tous les musiciens depuis la base de données
        $musiciens = $musicienRepository->findAll();

        // Retourne la vue avec la liste des musiciens
        return $this->render('musicien/index.html.twig', [
            'musiciens' => $musiciens,
        ]);
    }

    // Route pour créer un nouveau musicien
    #[Route('/musicien/new', name: 'musicien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Crée une nouvelle instance de l'entité Musicien
        $musicien = new Musicien();

        // Crée le formulaire pour saisir les informations du musicien
        $form = $this->createForm(MusicienType::class, $musicien);

        // Traite la requête du formulaire
        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persiste les données du nouveau musicien
            $entityManager->persist($musicien);
            $entityManager->flush();

            // Redirige vers la liste des musiciens après la création
            return $this->redirectToRoute('musicien_index');
        }

        // Affiche la vue du formulaire pour la création d'un musicien
        return $this->render('musicien/new.html.twig', [
            'musicien' => $musicien,
            'form' => $form->createView(),
        ]);
    }

    // Route pour afficher les détails d'un musicien spécifique
    #[Route('/musicien/{id}', name: 'musicien_show', methods: ['GET'])]
    public function show(Musicien $musicien): Response
    {
        // Affiche la vue de détails du musicien sélectionné
        return $this->render('musicien/show.html.twig', [
            'musicien' => $musicien,
        ]);
    }

    // Route pour modifier un musicien existant
    #[Route('/musicien/{id}/edit', name: 'musicien_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Musicien $musicien, EntityManagerInterface $entityManager): Response
    {
        // Crée le formulaire pour modifier les informations du musicien
        $form = $this->createForm(MusicienType::class, $musicien);
        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Met à jour les données dans la base
            $entityManager->flush();

            // Redirige vers la liste des musiciens après la modification
            return $this->redirectToRoute('musicien_index');
        }

        // Affiche la vue du formulaire de modification du musicien
        return $this->render('musicien/edit.html.twig', [
            'musicien' => $musicien,
            'form' => $form->createView(),
        ]);
    }

    // Route pour supprimer un musicien
    #[Route('/musicien/{id}', name: 'musicien_delete', methods: ['POST'])]
    public function delete(Request $request, Musicien $musicien, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si le token CSRF est valide pour autoriser la suppression
        if ($this->isCsrfTokenValid('delete'.$musicien->getId(), $request->request->get('_token'))) {
            // Supprime le musicien de la base de données
            $entityManager->remove($musicien);
            $entityManager->flush();
        }

        // Redirige vers la liste des musiciens après la suppression
        return $this->redirectToRoute('musicien_index');
    }
}
