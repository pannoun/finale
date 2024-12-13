<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontTacheController extends AbstractController
{
    #[Route('/front/taches', name: 'front_tache_index', methods: ['GET'])]
    public function index(Request $request, TacheRepository $tacheRepository): Response
    {
        $search = $request->query->get('search');

        // Si un terme de recherche est fourni, on utilise une méthode personnalisée pour filtrer
        $taches = $search 
            ? $tacheRepository->searchByKeyword($search) 
            : $tacheRepository->findAll();

        return $this->render('front_tache/index.html.twig', [
            'taches' => $taches,
            'search' => $search, // Pour réafficher la recherche dans le champ
        ]);
    }

    #[Route('/front/taches/{id}', name: 'front_tache_show', methods: ['GET', 'POST'])]
    public function show(
        int $id, 
        TacheRepository $tacheRepository, 
        Request $request, 
        EntityManagerInterface $entityManager
    ): Response {
        $tache = $tacheRepository->find($id);

        if (!$tache) {
            throw $this->createNotFoundException('Tâche non trouvée');
        }

        // Création d'un nouveau commentaire lié à la tâche
        $commentaire = new Commentaire();
        $commentaire->setTache($tache);

        // Création et gestion du formulaire
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès !');
            return $this->redirectToRoute('front_tache_show', ['id' => $id]);
        }

        return $this->render('front_tache/show.html.twig', [
            'tache' => $tache,
            'form' => $form->createView(),
        ]);
    }
}
