<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailVerificationController extends AbstractController
{
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyEmail(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $token = $request->query->get('token');

        if (!$token) {
            throw $this->createNotFoundException('Token manquant');
        }

        $user = $userRepository->findOneBy(['verificationToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé pour ce token');
        }

        $user->setIsVerified(true);
        $user->setVerificationToken(null);
        $entityManager->flush();

        $this->addFlash('success', 'Votre email a été confirmé avec succès.');

        return $this->redirectToRoute('app_login');
    }
}
