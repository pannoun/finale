<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Form\TacheType;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/tache')]
class TacheController extends AbstractController
{
    private const UPLOADS_DIRECTORY = 'uploads/taches';

    // Reusable helper to find a Tache or throw an exception
    private function findTacheOrThrow(int $id, TacheRepository $repository): Tache
    {
        $tache = $repository->find($id);
        if (!$tache) {
            throw $this->createNotFoundException('Task not found.');
        }
        return $tache;
    }

    // Show Task Details
    #[Route('/show/{id}', name: 'app_tache_show', methods: ['GET'])]
    public function show(int $id, TacheRepository $tacheRepository): Response
    {
        $tache = $this->findTacheOrThrow($id, $tacheRepository);
        return $this->render('tache/show.html.twig', [
            'tache' => $tache,
        ]);
    }

    // Download Task File
    #[Route('/download/{id}', name: 'app_tache_download', methods: ['GET'])]
    public function download(int $id, TacheRepository $tacheRepository): Response
    {
        $tache = $this->findTacheOrThrow($id, $tacheRepository);
        $filePath = self::UPLOADS_DIRECTORY . '/' . $tache->getFile();

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('File not found.');
        }

        return $this->file($filePath, $tache->getFile());
    }

    // Create a New Task
    #[Route('/new', name: 'app_tache_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tache = new Tache();
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($file instanceof UploadedFile) {
                $newFilename = uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(self::UPLOADS_DIRECTORY, $newFilename);
                    $tache->setFile($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'File upload failed: ' . $e->getMessage());
                }
            }

            $entityManager->persist($tache);
            $entityManager->flush();

            $this->addFlash('success', 'Task created successfully!');
            return $this->redirectToRoute('app_tache_index');
        }

        return $this->render('tache/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Edit an Existing Task
    #[Route('/{id}/edit', name: 'app_tache_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tache $tache, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($file instanceof UploadedFile) {
                $newFilename = uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(self::UPLOADS_DIRECTORY, $newFilename);
                    $tache->setFile($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'File upload failed: ' . $e->getMessage());
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Task updated successfully!');
            return $this->redirectToRoute('app_tache_index');
        }

        return $this->render('tache/edit.html.twig', [
            'form' => $form->createView(),
            'tache' => $tache,
        ]);
    }

    // Delete Task
    #[Route('/{id}', name: 'app_tache_delete', methods: ['POST'])]
    public function delete(Request $request, Tache $tache, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tache->getId(), $request->request->get('_token'))) {
            $filePath = self::UPLOADS_DIRECTORY . '/' . $tache->getFile();

            if ($tache->getFile() && file_exists($filePath)) {
                unlink($filePath); // Remove the file
            }

            $entityManager->remove($tache);
            $entityManager->flush();
            $this->addFlash('success', 'Task deleted successfully!');
        }

        return $this->redirectToRoute('app_tache_index');
    }

    // Index: List All Tasks with Filters
    #[Route('/', name: 'app_tache_index', methods: ['GET'])]
    public function index(Request $request, TacheRepository $tacheRepository): Response
    {
        $title = $request->query->get('title');
        $startDate = $request->query->get('startDate') ? new \DateTime($request->query->get('startDate')) : null;
        $endDate = $request->query->get('endDate') ? new \DateTime($request->query->get('endDate')) : null;

        $taches = $tacheRepository->findTasksByFilters($title, $startDate, $endDate);

        return $this->render('tache/index.html.twig', [
            'taches' => $taches,
        ]);
    }
}
