<?php

namespace App\Controller;

use App\Repository\MusicRepository;
use App\Repository\CollaborationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscoverController extends AbstractController
{
    #[Route('/discover', name: 'discover')]
    public function index(MusicRepository $musicRepository, CollaborationRepository $collaborationRepository): Response
    {
        // Récupération de toutes les musiques et collaborations
        $musics = $musicRepository->findAll();
        $collaborations = $collaborationRepository->findAll();

        return $this->render('discover/index.html.twig', [
            'musics' => $musics,
            'collaborations' => $collaborations,
        ]);
    }
}
