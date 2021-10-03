<?php

namespace App\Repository;

use App\Entity\PaquetRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaquetRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaquetRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaquetRequest[]    findAll()
 * @method PaquetRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaquetRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaquetRequest::class);
    }

    // /**
    //  * @return PaquetRequest[] Returns an array of PaquetRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaquetRequest
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
