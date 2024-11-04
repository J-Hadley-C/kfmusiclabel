<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Artist;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
            // Création d'un nouvel artiste
            $artist = new Artist();
            $artist->setUser($user);
            $artist->setName($form->get('nickname')->getData());
            $user->setArtist($artist);

            // Gestion des rôles de l'utilisateur
            $roles = $form->get('roles')->getData();
            if (!in_array('ROLE_ARTIST', $roles)) {
                $roles[] = 'ROLE_ARTIST';
            }
            $user->setRoles($roles);

            // Hashage du mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($hashedPassword);

            // Génération du token de vérification
            $verificationToken = bin2hex(random_bytes(32));
            $user->setVerificationToken($verificationToken);

            try {
                $entityManager->persist($artist);
                $entityManager->persist($user);
                $entityManager->flush();

                // Envoi de l'email de vérification
                $verificationUrl = $this->generateUrl(
                    'app_verify_email',
                    ['token' => $verificationToken],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $email = (new TemplatedEmail())
                    ->from('noreply@votresite.com')
                    ->to($user->getEmail())
                    ->subject('Vérifiez votre adresse email')
                    ->htmlTemplate('security/verification_email.html.twig')
                    ->context([
                        'user' => $user, // Passe explicitement l'objet user
                        'verificationUrl' => $verificationUrl
                    ]);

                $mailer->send($email);

                $this->addFlash('success', 'Un email de vérification a été envoyé. Veuillez vérifier votre boîte de réception.');

                return $this->redirectToRoute('app_login');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Cet email est déjà utilisé. Veuillez en choisir un autre.');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
