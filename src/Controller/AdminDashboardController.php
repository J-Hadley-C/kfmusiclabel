<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use App\Repository\CollaborationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(ArtistRepository $artistRepository, CollaborationRepository $collaborationRepository): Response
    {
        // Récupère tous les artistes de la base de données
        $artists = $artistRepository->findAll();

        // Récupère toutes les collaborations de la base de données
        $collaborations = $collaborationRepository->findAll();

        // Définit les rôles spécifiques des sous-entités d'artistes
        $roles = [
            'ROLE_PRODUCTEUR' => 'producers',    // Producteurs
            'ROLE_BEATMAKER' => 'beatmakers',    // Beatmakers
            'ROLE_CHANTEUR' => 'singers',        // Chanteurs
            'ROLE_MUSICIAN' => 'musicians'       // Musiciens
        ];

        // Tableau pour stocker les artistes filtrés par sous-entité
        $filteredArtists = [];

        // Filtre les artistes selon leur rôle
        foreach ($roles as $role => $key) {
            $filteredArtists[$key] = array_filter($artists, fn($artist) => in_array($role, $artist->getUser()->getRoles()));
        }

        // Rend la vue du tableau de bord administrateur
        return $this->render('admin_dashboard/index.html.twig', [
            'artists' => $artists,
            'producers' => $filteredArtists['producers'], // Producteurs
            'beatmakers' => $filteredArtists['beatmakers'], // Beatmakers
            'singers' => $filteredArtists['singers'], // Chanteurs
            'musicians' => $filteredArtists['musicians'], // Musiciens
            'collaborations' => $collaborations, // Collaborations
        ]);
    }
}
