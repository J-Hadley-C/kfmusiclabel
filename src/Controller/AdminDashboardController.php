<?php

namespace App\Controller;

use App\Repository\CollaborationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * Affiche le tableau de bord pour l'administrateur avec une vue des utilisateurs et collaborations.
     *
     * @param UserRepository $userRepository Injecte le repository pour les utilisateurs
     * @param CollaborationRepository $collaborationRepository Injecte le repository pour les collaborations
     * @return Response La vue du tableau de bord de l'administrateur
     */
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(UserRepository $userRepository, CollaborationRepository $collaborationRepository): Response
    {
        // Récupère les utilisateurs par rôle pour les afficher séparément
        $artists = $userRepository->findByRole('ROLE_ARTIST'); // Liste des artistes
        $producers = $userRepository->findByRole('ROLE_PRODUCTEUR'); // Liste des producteurs
        $beatmakers = $userRepository->findByRole('ROLE_BEATMAKER'); // Liste des beatmakers
        $musicians = $userRepository->findByRole('ROLE_MUSICIAN'); // Liste des musiciens

        // Récupère toutes les collaborations pour affichage, sans contrôle d'édition
        $collaborations = $collaborationRepository->findAll();

        // Retourne le template du tableau de bord d'administration
        return $this->render('admin_dashboard/index.html.twig', [
            'artists' => $artists,
            'producers' => $producers,
            'beatmakers' => $beatmakers,
            'musicians' => $musicians,
            'collaborations' => $collaborations,
        ]);
    }
}
