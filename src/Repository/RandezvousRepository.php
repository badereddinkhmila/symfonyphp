<?php

namespace App\Repository;

use App\Entity\Randezvous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Randezvous|null find($id, $lockMode = null, $lockVersion = null)
 * @method Randezvous|null findOneBy(array $criteria, array $orderBy = null)
 * @method Randezvous[]    findAll()
 * @method Randezvous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RandezvousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Randezvous::class);
    }

    // /**
    //  * @return Randezvous[] Returns an array of Randezvous objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Randezvous
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
