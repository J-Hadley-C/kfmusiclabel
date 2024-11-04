<?php

namespace App\Controller;

use App\Entity\Collaboration;
use App\Form\CollaborationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CollaborationController extends AbstractController
{
    // Route pour afficher la liste des collaborations
    #[Route('/collaborations', name: 'collaborations_list')]
    public function list(CollaborationRepository $collaborationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ARTIST');

        $collaborations = $collaborationRepository->findAll();

        return $this->render('collaboration/list.html.twig', [
            'collaborations' => $collaborations,
        ]);
    }

    // Route pour créer une nouvelle collaboration
    #[Route('/collaborations/new', name: 'collaborations_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ARTIST');

        $collaboration = new Collaboration();
        $form = $this->createForm(CollaborationType::class, $collaboration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $collaboration->setInitiator($this->getUser()->getArtist());
            $collaboration->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($collaboration);
            $entityManager->flush();

            return $this->redirectToRoute('collaborations_list');
        }

        return $this->render('collaboration/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route pour afficher les détails d'une collaboration
    #[Route('/collaborations/{id}', name: 'collaboration_detail')]
    public function detail(Collaboration $collaboration): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ARTIST');

        return $this->render('collaboration/detail.html.twig', [
            'collaboration' => $collaboration,
        ]);
    }
}
