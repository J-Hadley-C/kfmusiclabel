<?php

namespace App\Controller;

use App\Entity\Music;
use App\Form\UploadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class MusicController extends AbstractController
{
    #[Route('/music/upload', name: 'music_upload')]
    public function upload(Request $request, EntityManagerInterface $em, UserInterface $user): Response
    {
        // Récupère l'entité Artiste associée à l'utilisateur actuel
        $artist = $user->getArtist();
        $musicCount = $artist ? count($artist->getMusics()) : 0;

        if ($musicCount >= 3) {
            $this->addFlash('error', 'Vous avez atteint la limite de 3 fichiers.');
            return $this->redirectToRoute('artist_dashboard');
        }

        $music = new Music();
        $form = $this->createForm(UploadType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('link')->getData();
            $image = $form->get('cover')->getData();
            $musicName = uniqid('music-');

            if ($file) {
                $fileName = $musicName . '.' . $file->guessExtension();
                $file->move($this->getParameter('uploads_directory'), $fileName);
                $music->setFilePath($fileName);
            }

            if ($image) {
                $coverFileName = $musicName . '-cover.' . $image->guessExtension();
                $image->move($this->getParameter('uploads_directory'), $coverFileName);
                $music->setCover($coverFileName);
            }

            $music->setArtist($artist);
            $em->persist($music);
            $em->flush();

            $this->addFlash('success', 'Votre musique a été uploadée !');
            return $this->redirectToRoute('music_upload_ok', ['id' => $music->getId()]);
        }

        return $this->render('music/upload.html.twig', [
            'uploadForm' => $form->createView(),
        ]);
    }

    #[Route('/cover/upload', name: 'cover_upload', methods: ['POST'])]
    public function uploadCover(Request $request): Response
    {
        $file = $request->files->get('file');
        if (!$file) {
            return $this->json(['error' => 'Aucun fichier uploadé'], 400);
        }

        $coverFileName = uniqid() . '.' . $file->guessExtension();
        $file->move($this->getParameter('uploads_directory'), $coverFileName);

        return $this->json(['cover' => $coverFileName], 200);
    }

    #[Route('/music/ok/{id}', name: 'music_upload_ok')]
    public function uploadOk(Music $music): Response
    {
        return $this->render('music/upload_ok.html.twig', [
            'music' => $music
        ]);
    }
}
