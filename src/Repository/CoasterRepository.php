<?php

namespace App\Repository;

use App\Entity\Coaster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coaster>
 */
class CoasterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coaster::class);
    }

    public function findFiltered(int $parkId = 0, int $categoryId = 0, string $search = '', int $count = 30, int $page = 1, ): Paginator
    {
        $qb = $this->createQueryBuilder('c')
            ->addSelect('p', 'ca') // Doctrine va charger le parc et les catégories en meme temps
            ->leftJoin('c.park', 'p')
            ->leftJoin('c.categories', 'ca')
        ;

        if ($parkId > 0) {
            $qb->andWhere('p.id = :parkId')
                ->setParameter('parkId', $parkId)
            ;
        }

        if ($categoryId > 0) {
            $qb->andWhere('ca.id = :categoryId')
                ->setParameter('categoryId', $categoryId)
            ;
        }

        if (strlen($search) > 2 ) {
            $qb->andWhere('c.name LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }

        $begin = ($page - 1) * $count; // Calcul de l'offset

        $qb->setMaxResults($count) // LIMIT
            ->setFirstResult($begin); // OFFSET

        return new Paginator($qb->getQuery());
    }

    //    /**
    //     * @return Coaster[] Returns an array of Coaster objects
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

    //    public function findOneBySomeField($value): ?Coaster
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
