<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class ForgotPasswordController extends AbstractController
{
    // Route pour demander la réinitialisation du mot de passe
    #[Route('/reset-password', name: 'app_forgot_password_request')]
    public function requestResetPassword(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                $user->setResetToken(bin2hex(random_bytes(32)));
                $entityManager->flush();

                $resetUrl = $this->generateUrl(
                    'app_reset_password',
                    ['token' => $user->getResetToken()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $resetEmail = (new TemplatedEmail())
                    ->from('noreply@votresite.com')
                    ->to($user->getEmail())
                    ->subject('Réinitialisez votre mot de passe')
                    ->htmlTemplate('security/emails/reset_password_email.html.twig')
                    ->context(['resetUrl' => $resetUrl]);

                $mailer->send($resetEmail);

                // Ajoute le message flash pour informer l'utilisateur
                $this->addFlash('info', 'Un email de réinitialisation a été envoyé. Veuillez vérifier votre boîte mail et cliquer sur le lien pour réinitialiser votre mot de passe.');
            } else {
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cet email.');
            }
        }

        return $this->render('security/request_reset_password.html.twig');
    }

    // Route pour la réinitialisation du mot de passe à partir du lien envoyé par email
    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request $request, string $token, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Ce lien de réinitialisation est invalide.');
        }

        if ($request->isMethod('POST')) {
            $newPassword = $request->request->get('password');

            // Vérification de la longueur du mot de passe
            if (strlen($newPassword) < 8) {
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            }

            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
            $user->setResetToken(null);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', ['token' => $token]);
    }
}