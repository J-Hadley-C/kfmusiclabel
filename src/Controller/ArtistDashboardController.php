<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Music;
use App\Form\UploadType;
use Doctrine\ORM\EntityManagerInterface;

class ArtistDashboardController extends AbstractController
{
    // Route pour accéder au tableau de bord de l'artiste
    #[Route('/artist/dashboard', name: 'artist_dashboard')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur a le rôle "ROLE_ARTIST"
        if (!$this->isGranted('ROLE_ARTIST')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour accéder à cette section.');
        }

        // Récupère l'utilisateur actuellement connecté
        $user = $this->getUser();
        
        // Vérifie que l'utilisateur est bien associé à un artiste
        $artist = $user ? $user->getArtist() : null;

        // Si l'utilisateur n'est pas associé à un artiste, retourne une erreur
        if (!$artist) {
            throw $this->createNotFoundException('Artiste non trouvé.');
        }

        // Vérifie si l'utilisateur a le rôle d'administrateur
        $isAdmin = $user && in_array('ROLE_ADMIN', $user->getRoles());

        // Récupère la liste des musiques
        if ($isAdmin) {
            // Si l'utilisateur est administrateur, récupère toutes les musiques
            $musics = $entityManager->getRepository(Music::class)->findAll();
        } else {
            // Sinon, récupère uniquement les musiques associées à cet artiste
            $musics = $artist->getMusics();
        }

        // Gestion de la limite d'upload de musique
        $musicCount = count($musics);
        $uploadForm = null;

        // Si l'artiste n'a pas encore atteint la limite de 3 musiques, on affiche le formulaire d'upload
        if ($musicCount < 3) {
            $music = new Music();
            $uploadForm = $this->createForm(UploadType::class, $music);
            $uploadForm->handleRequest($request);

            // Si le formulaire est soumis et valide, on enregistre la musique
            if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
                $file = $uploadForm->get('link')->getData();

                if ($file) {
                    $fileName = uniqid() . '.' . $file->guessExtension();
                    $file->move($this->getParameter('uploads_directory'), $fileName);
                    $music->setFilePath($fileName);
                    $music->setArtist($artist);

                    $entityManager->persist($music);
                    $entityManager->flush();

                    $this->addFlash('success', 'Votre musique a été uploadée avec succès !');

                    return $this->redirectToRoute('artist_dashboard');
                }
            }
        } else {
            $this->addFlash('error', 'Vous avez atteint la limite de 3 musiques.');
        }

        // Retourne la vue du tableau de bord de l'artiste avec ses informations
        return $this->render('artist_dashboard/index.html.twig', [
            'artist' => $artist,
            'isAdmin' => $isAdmin,
            'musics' => $musics,
            'uploadForm' => $uploadForm ? $uploadForm->createView() : null,
        ]);
    }
}
