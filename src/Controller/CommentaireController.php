<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Tache;
use App\Form\CommentaireType;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/commentaire')]
class CommentaireController extends AbstractController
{
    // Afficher les commentaires pour une tâche spécifique
    #[Route('/{tacheId}', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(int $tacheId, TacheRepository $tacheRepository): Response
    {
        $tache = $tacheRepository->find($tacheId);

        if (!$tache) {
            throw $this->createNotFoundException('Tâche non trouvée.');
        }

        return $this->render('commentaire/index.html.twig', [
            'tache' => $tache,
            'commentaires' => $tache->getCommentaires(),
        ]);
    }

    // Ajouter un nouveau commentaire pour une tâche
    #[Route('/new/{tacheId}', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $tacheId, TacheRepository $tacheRepository): Response
    {
        $tache = $tacheRepository->find($tacheId);

        if (!$tache) {
            throw $this->createNotFoundException('Tâche non trouvée.');
        }

        $commentaire = new Commentaire();
        $commentaire->setTache($tache); // Associer le commentaire à la tâche

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès !');

            // Rediriger vers la page des commentaires de la tâche
            return $this->redirectToRoute('app_commentaire_index', ['tacheId' => $tacheId]);
        }

        return $this->render('commentaire/new.html.twig', [
            'form' => $form->createView(),
            'tache' => $tache,
        ]);
    }

    // Modifier un commentaire
    #[Route('/commentaire/edit/{id}', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Rediriger vers les commentaires de la tâche
            return $this->redirectToRoute('app_commentaire_index', ['tacheId' => $commentaire->getTache()->getId()]);
        }

        return $this->render('commentaire/edit.html.twig', [
            'form' => $form->createView(),
            'commentaire' => $commentaire,
        ]);
    }

    // Supprimer un commentaire
    #[Route('/delete/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_index', ['tacheId' => $commentaire->getTache()->getId()]);
    }
}
