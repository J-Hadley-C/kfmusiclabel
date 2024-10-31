<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Uid\Uuid;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hashage du mot de passe
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // Ajout des rôles choisis par l'utilisateur
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);

            // Génération d'un token de vérification unique
            $user->setVerificationToken(Uuid::v4());

            // Enregistrement de l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoi de l'email de confirmation
            $email = (new TemplatedEmail())
                ->from('noreply@example.com')
                ->to($user->getEmail())
                ->subject('Confirmez votre adresse email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
                ->context([
                    'token' => $user->getVerificationToken(),
                ]);

            $mailer->send($email);

            // Notification de l'envoi de l'email de confirmation
            $this->addFlash('success', 'Un email de confirmation vous a été envoyé. Veuillez vérifier votre boîte de réception.');

            // Redirection vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
