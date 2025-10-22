<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Backlog;
use App\Entity\BacklogItem;
use App\Enum\BacklogItemStatus;
use App\Enum\BacklogItemType;

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

    #[Route('/search', name: 'app_spotify_search', methods: ['GET'])]
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

    #[Route('/{id}', name: 'app_backlog_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Backlog $backlog): Response
    {
        return $this->render('backlog/show.html.twig', [
            'backlog' => $backlog,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backlog_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
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

    #[Route('/{id}', name: 'app_backlog_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Backlog $backlog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$backlog->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($backlog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backlog_index', [], Response::HTTP_SEE_OTHER);
    }

    
    #[Route('/additem', name: 'app_backlog_additem', methods: ['POST'])]
    public function addToBacklog(Request $request, EntityManagerInterface $entityManager): Response
    {
        $type = $request->request->get('type');
        $name = $request->request->get('name');
        $spotifyId = $request->request->get('spotify_id');
        $image = $request->request->get('image');
        $releaseDate = $type === 'album' ? $request->request->get('release_date') : null;

        // dd($type, $name, $spotifyId, $image, $releaseDate);

        if (!$type || !$spotifyId) {
            $this->addFlash('error', 'Missing data.');
            return $this->redirectToRoute('app_backlog_index');
        }

        // Logic to add the item to the backlog
        if ($type === 'artist') {
            // Add artist to backlog
            $entity = $entityManager->getRepository(Artist::class)->findOneBy(['spotify_id' => $spotifyId]);
            if (!$entity) {
                $entity = new Artist();
                $entity->setName($name);
                $entity->setSpotifyId($spotifyId);
                $entity->setImage($image);

                $entityManager->persist($entity);
            }
        } elseif ($type === 'album') {
            // Add album to backlog
            $entity = $entityManager->getRepository(Album::class)->findOneBy(['spotify_id' => $spotifyId]);
            if (!$entity) {
                $entity = new Album();
                $entity->setName($name);
                $entity->setSpotifyId($spotifyId);
                $entity->setImage($image);

                if ($releaseDate) {
                    $FormattedReleaseDate = \DateTime::createFromFormat('Y-m-d', $releaseDate);
                    $entity->setReleaseDate($FormattedReleaseDate);
                }

                $entityManager->persist($entity);
            }
        }

        // Add to a default backlog
        $backlog = $entityManager->getRepository(Backlog::class)->findOneBy(['id' => 5]);
        if (!$backlog) {
            $this->addFlash('error', 'Backlog not found.');
            return $this->redirectToRoute('app_backlog_index');
        }

        $item = new BacklogItem();
        $item->setBacklog($backlog);
        $item->setStatus(BacklogItemStatus::PLANNED);

        if ($type === 'artist') {
            $item->setType(BacklogItemType::ARTIST);
            $item->setArtist($entity);
        } else {
            $item->setType(BacklogItemType::ALBUM);
            $item->setAlbum($entity);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        $this->addFlash('success', 'Added to backlog.');
        return $this->redirectToRoute('app_backlog_index');
    }
}
