<?php

namespace App\Controller;

use App\Entity\Backlog;
use App\Form\BacklogType;
use App\Repository\BacklogRepository;
use App\Service\SpotifyAPIService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backlog')]
final class BacklogController extends AbstractController
{
    #[Route(name: 'app_backlog_index', methods: ['GET'])]
    public function index(BacklogRepository $backlogRepository): Response
    {
        return $this->render('backlog/index.html.twig', [
            'backlogs' => $backlogRepository->findAll(),
        ]);
    }

    #[Route('/spotify/search', name: 'app_spotify_search', methods: ['GET'])]
    public function search(Request $request, SpotifyAPIService $spotifyAPI): Response
    {
        $query = $request->query->get('query', '');

        if (empty($query)) {
            return $this->redirectToRoute('app_backlog_index');
        }

        $results = $spotifyAPI->search($query);
        // dd($results); // Dumps JSON to browser

        return $this->render('backlog/search.html.twig', [
            'results' => $results,
            'query' => $query,
        ]);
    }

    #[Route('/new', name: 'app_backlog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $backlog = new Backlog();
        $form = $this->createForm(BacklogType::class, $backlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($backlog);
            $entityManager->flush();

            return $this->redirectToRoute('app_backlog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backlog/new.html.twig', [
            'backlog' => $backlog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backlog_show', methods: ['GET'])]
    public function show(Backlog $backlog): Response
    {
        return $this->render('backlog/show.html.twig', [
            'backlog' => $backlog,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backlog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Backlog $backlog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BacklogType::class, $backlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_backlog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backlog/edit.html.twig', [
            'backlog' => $backlog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backlog_delete', methods: ['POST'])]
    public function delete(Request $request, Backlog $backlog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$backlog->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($backlog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backlog_index', [], Response::HTTP_SEE_OTHER);
    }
}
