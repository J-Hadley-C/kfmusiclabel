<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use App\Repository\FollowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProducteurDashboardController extends AbstractController
{
    // Définition de la route pour accéder au tableau de bord du producteur
    #[Route('/producteur/dashboard', name: 'producteur_dashboard')]
    public function index(FollowRepository $followRepository, ArtistRepository $artistRepository): Response
    {
        // Récupère l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Vérifie si l'utilisateur est admin, permettant l'accès sans entité Producteur
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            // Récupère tous les artistes pour l'admin
            $allArtists = $artistRepository->findAll();

            return $this->render('producteur_dashboard/index.html.twig', [
                'followedArtists' => [],   // Pas de suivi spécifique pour l'admin
                'allArtists' => $allArtists,
                'isAdmin' => true,
            ]);
        }

        // Vérifie si l'utilisateur est bien un Producteur
        if (!$user->getProducteur()) {
            throw $this->createNotFoundException('Producteur non trouvé.');
        }

        // Récupère l'objet producteur associé à l'utilisateur connecté
        $producteur = $user->getProducteur();

        // Récupère les artistes suivis par le producteur
        $followedArtists = $followRepository->findBy(['prod' => $producteur]);

        // Récupère tous les artistes
        $allArtists = $artistRepository->findAll();

        return $this->render('producteur_dashboard/index.html.twig', [
            'followedArtists' => $followedArtists,
            'allArtists' => $allArtists,
            'isAdmin' => false,
        ]);
    }
}
