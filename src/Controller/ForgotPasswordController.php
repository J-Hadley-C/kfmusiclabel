<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractController
{
    // Route pour afficher le formulaire de demande de réinitialisation de mot de passe
    #[Route('/forgot-password', name: 'app_forgot_password_request')]
    public function request(Request $request): Response
    {
        // Afficher un formulaire demandant l'email de l'utilisateur
        return $this->render('security/forgot_password.html.twig', [
            'controller_name' => 'ForgotPasswordController',
        ]);
    }
}
