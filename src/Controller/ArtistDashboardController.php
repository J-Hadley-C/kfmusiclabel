<?php

namespace App\Controller;

use App\Repository\MusicRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtistDashboardController extends AbstractController
{
    #[Route('/artist/dashboard', name: 'artist_dashboard')]
    public function index(MusicRepository $musicRepository): Response
    {
        $user = $this->getUser();

        // Permettre l'accès pour les administrateurs même sans entité Artist
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            // Admin peut accéder au dashboard sans entité spécifique
            $musics = $musicRepository->findAll(); // Admin voit toutes les musiques
            return $this->render('artist_dashboard/index.html.twig', [
                'musics' => $musics,
                'isAdmin' => true,
            ]);
        }

        // Vérifie si l'utilisateur est bien un Artiste
        $artist = $user->getArtist();
        if (!$artist) {
            throw $this->createNotFoundException('Artiste non trouvé.');
        }

        // Récupère les musiques de l'artiste
        $musics = $musicRepository->findBy(['artist' => $artist]);

        return $this->render('artist_dashboard/index.html.twig', [
            'musics' => $musics,
            'isAdmin' => false,
        ]);
    }
}
