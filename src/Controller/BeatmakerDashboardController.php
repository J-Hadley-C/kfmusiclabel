<?php

namespace App\Controller;

use App\Repository\MusicRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BeatmakerDashboardController extends AbstractController
{
    #[Route('/beatmaker/dashboard', name: 'beatmaker_dashboard')]
    public function index(MusicRepository $musicRepository): Response
    {
        $user = $this->getUser();

        // Permet l'accès aux administrateurs même sans entité Beatmaker
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $producedMusics = $musicRepository->findAll(); // Admin voit toutes les musiques produites
            return $this->render('beatmaker_dashboard/index.html.twig', [
                'producedMusics' => $producedMusics,
                'isAdmin' => true,
            ]);
        }

        // Vérifie que l'utilisateur est bien un Beatmaker
        $beatmaker = $user->getBeatmaker();
        if (!$beatmaker) {
            throw $this->createNotFoundException('Beatmaker non trouvé.');
        }

        // Récupère les musiques produites par le beatmaker
        $producedMusics = $musicRepository->findBy(['beatmaker' => $beatmaker]);

        return $this->render('beatmaker_dashboard/index.html.twig', [
            'producedMusics' => $producedMusics,
            'isAdmin' => false,
        ]);
    }
}
