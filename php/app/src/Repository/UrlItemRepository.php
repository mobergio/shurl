<?php

namespace App\Repository;

use App\Entity\UrlItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UrlItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlItem[]    findAll()
 * @method UrlItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlItemRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrlItem::class);
    }

    // /**
    //  * @return UrlItem[] Returns an array of UrlItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UrlItem
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
