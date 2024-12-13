<?php

namespace App\Repository;

use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentaire>
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }
    public function findCommentsByTask(int $tacheId, ?\DateTime $startDate = null, ?\DateTime $endDate = null): array
{
    $qb = $this->createQueryBuilder('c')
        ->where('c.tache = :tacheId')
        ->setParameter('tacheId', $tacheId);

    // Filtering by start date if provided
    if ($startDate) {
        $qb->andWhere('c.dateCommentaire >= :startDate')
            ->setParameter('startDate', $startDate);
    }

    // Filtering by end date if provided
    if ($endDate) {
        $qb->andWhere('c.dateCommentaire <= :endDate')
            ->setParameter('endDate', $endDate);
    }

    return $qb->getQuery()->getResult();
}


//    /**
//     * @return Commentaire[] Returns an array of Commentaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Commentaire
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
