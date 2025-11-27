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
use App\Repository\BacklogItemRepository;
use App\Service\SpotifyAPIService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;

#[Route('/backlog')]
final class BacklogController extends AbstractController
{
    #[Route( name: 'app_backlog_index', methods: ['GET'])]
    public function index(BacklogRepository $backlogRepository): Response
    {
        return $this->render('backlog/index.html.twig');
    }

    #[Route('/overview', name: 'app_backlog_overview', methods: ['GET'])]
    public function overview(BacklogRepository $backlogRepository): Response
    {
        return $this->render('backlog/overview.html.twig', [
            'backlogs' => $backlogRepository->findAll(),
        ]);
    }

    #[Route('/search', name: 'app_spotify_search', methods: ['GET'])]
    public function search(Request $request, SpotifyAPIService $spotifyAPI, BacklogRepository $backlogRepository): Response
    {
        $query = $request->query->get('query', '');

        if (empty($query)) {
            return $this->redirectToRoute('app_backlog_overview');
        }

        $results = $spotifyAPI->search($query);

        return $this->render('backlog/search.html.twig', [
            'results' => $results,
            'query' => $query,
            'backlogs' => $backlogRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_backlog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $backlog = new Backlog();
        $form = $this->createForm(BacklogType::class, $backlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($backlog->getName())->lower()->toString();
            $backlog->setSlug($slug);

            $entityManager->persist($backlog);
            $entityManager->flush();

            return $this->redirectToRoute('app_backlog_overview', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backlog/new.html.twig', [
            'backlog' => $backlog,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}/{slug}', name: 'app_backlog_show', methods: ['GET'])]
    public function show(BacklogRepository $backlogRepository, string $uuid, string $slug ): Response
    {
        $backlog = $backlogRepository->findOneBy([
            'uuid' => Uuid::fromString($uuid),
            'slug' => $slug
        ]);

        if (!$backlog) {
            throw $this->createNotFoundException('Backlog not found.');
        }

        return $this->render('backlog/show.html.twig', [
            'backlog' => $backlog,
        ]);
    }

    #[Route('/{uuid}/{slug}/edit', name: 'app_backlog_edit', methods: ['GET', 'POST'], requirements: ['uuid' => '[0-9a-f-]{36}'])]
    public function edit(Request $request, BacklogRepository $backlogRepository, string $uuid, string $slug, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $backlog = $backlogRepository->findOneBy([
            'uuid' => Uuid::fromString($uuid),
            'slug' => $slug
        ]);

        if (!$backlog) {
            throw $this->createNotFoundException('Backlog not found.');
        }

        $form = $this->createForm(BacklogType::class, $backlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($backlog->getName())->lower()->toString();
            $backlog->setSlug($slug);

            $entityManager->flush();

            return $this->redirectToRoute('app_backlog_show', [
                'uuid' => $backlog->getUuid(), 
                'slug' => $backlog->getSlug()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backlog/edit.html.twig', [
            'backlog' => $backlog,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_backlog_delete', methods: ['POST'], requirements: ['uuid' => '[0-9a-f-]{36}'])]
    public function delete(Request $request, BacklogRepository $backlogRepository, string $uuid, EntityManagerInterface $entityManager): Response
    {
        $backlog = $backlogRepository->findOneBy([
            'uuid' => Uuid::fromString($uuid)
        ]);

        if (!$backlog) {
            throw $this->createNotFoundException('Backlog not found.');
        }

        if ($this->isCsrfTokenValid('delete'.$backlog->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($backlog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backlog_overview', [], Response::HTTP_SEE_OTHER);
    }

    
    #[Route('/additem', name: 'app_backlog_additem', methods: ['POST'])]
    public function addToBacklog(Request $request, EntityManagerInterface $entityManager, BacklogRepository $backlogRepository, SpotifyAPIService $spotifyAPI): Response
    {
        $uuid = $request->request->get('backlog_uuid');
        $backlog = null;

        if ($uuid) {
            try { 
                $backlog = $backlogRepository->findOneBy([
                    'uuid' => Uuid::fromString($uuid)
                ]);
            } catch (Exception $e) {
                $backlog = null;
            }
        }

        if (!$backlog) {
            $this->addFlash('error', 'Backlog not found.');
            return $this->redirectToRoute('app_backlog_overview');
        }

        $type = $request->request->get('type');
        $name = $request->request->get('name');
        $spotifyId = $request->request->get('spotify_id');
        $image = $request->request->get('image');
        $releaseDate = $type === 'album' ? $request->request->get('release_date') : null;

        if (!$type || !$spotifyId) {
            $this->addFlash('error', 'Missing data.');
            return $this->redirectToRoute('app_backlog_overview');
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


                if ($releaseDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $releaseDate)) {
                    $FormattedReleaseDate = \DateTime::createFromFormat('Y-m-d', $releaseDate);
                    $entity->setReleaseDate($FormattedReleaseDate);
                } else {
                    $entity->setReleaseDate(null);
                }

                $artistsJson = $request->request->get('artists');
                $artists = json_decode($artistsJson, true) ?: [];

                // Handle multiple artists
                foreach ($artists as $artistData) {
                    $artistEntity = $entityManager->getRepository(Artist::class)->findOneBy(['spotify_id' => $artistData['spotify_id']]);
                    if (!$artistEntity) {
                        $artistEntity = new Artist();
                        $artistEntity->setName($artistData['name']);
                        $artistEntity->setSpotifyId($artistData['spotify_id']);

                        //Search for the artist image. The artist data on the album array doesn't include images by default.
                        $artistEntity->setImage($spotifyAPI->getArtistImage($artistData['spotify_id']));

                        $entityManager->persist($artistEntity);
                    }

                    $entity->addArtist($artistEntity);
                }

                $entityManager->persist($entity);
            }
        }

        // Check for existing item in backlog
        if ($type === 'artist') {
            $existing = $entityManager->getRepository(BacklogItem::class)->findOneBy([
                'backlog' => $backlog,
                'type'    => BacklogItemType::ARTIST,
                'artist'  => $entity,
            ]);
        } else {
            $existing = $entityManager->getRepository(BacklogItem::class)->findOneBy([
                'backlog' => $backlog,
                'type'    => BacklogItemType::ALBUM,
                'album'   => $entity,
            ]);
        }

        if ($existing) {
            $this->addFlash('info', 'This item is already in this backlog.');
            return $this->redirectToRoute('app_backlog_overview');
        }

        $item = new BacklogItem();
        $item->setBacklog($backlog);
        $item->setStatus(BacklogItemStatus::PLANNED);
        $item->setDateAdded(new \DateTime());

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
        return $this->redirectToRoute('app_backlog_overview');
    }

    #[Route('/deleteitem/{id}', name: 'app_backlog_deleteitem', methods: ['POST'])]
    public function deleteItem(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $item = $entityManager->getRepository(BacklogItem::class)->find($id);

        if (!$item) {
            throw $this->createNotFoundException('Item not found.');
        }

        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($item);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Item removed from backlog.');
        return $this->redirectToRoute('app_backlog_overview', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/item/edit-meta', name: 'app_backlog_edit_meta', methods: ['POST'])]
    public function editMetaData(Request $request, BacklogItemRepository $backlogItemRepository, EntityManagerInterface $entityManager): Response
    {
        $data = $request->toArray();
        $itemId = $data['item_id'] ?? null;
        if (!$itemId) {
            return $this->json(['success' => false, 'error' => 'Missing item_id'], 400);
        }
        
        $item = $backlogItemRepository->find($itemId);

        if (!$item) {
            throw $this->createNotFoundException('Item not found.');
        }

        if (isset($data['note'])) {
            $item->setNote($data['note']);
        }

        if (isset($data['status'])) {
            $status = $data['status'];
            $item->setStatus(BacklogItemStatus::from($status));
        }

        $entityManager->persist($item);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}