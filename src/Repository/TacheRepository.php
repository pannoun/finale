<?php

namespace App\Repository;

use App\Entity\Tache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tache>
 */
class TacheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tache::class);
    }

    /**
     * Recherche des tâches par titre ou description.
     */
    public function searchByKeyword(string $keyword): array
    {
        $qb = $this->createQueryBuilder('t');
        return $qb->where('t.titreTache LIKE :keyword')
                  ->orWhere('t.descriptionTache LIKE :keyword')
                  ->setParameter('keyword', '%' . $keyword . '%')
                  ->orderBy('t.dateCreation', 'DESC')
                  ->getQuery()
                  ->getResult();
    }


























    public function findTasksByFilters(?string $title = null, ?\DateTime $startDate = null, ?\DateTime $endDate = null): array
    {
        $qb = $this->createQueryBuilder('t');

        if ($title) {
            $qb->andWhere('t.titreTache LIKE :title')
               ->setParameter('title', '%' . $title . '%');
        }

        if ($startDate) {
            $qb->andWhere('t.dateCreation >= :startDate')
               ->setParameter('startDate', $startDate);
        }

        if ($endDate) {
            $qb->andWhere('t.dateEcheance <= :endDate')
               ->setParameter('endDate', $endDate);
        }

        $qb->orderBy('t.dateCreation', 'DESC'); // Trier par date de création

        return $qb->getQuery()->getResult();
    }
}

