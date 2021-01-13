<?php

namespace App\Repository;

use App\Entity\HeartBeat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HeartBeat|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeartBeat|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeartBeat[]    findAll()
 * @method HeartBeat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeartBeatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeartBeat::class);
    }

    // /**
    //  * @return HeartBeat[] Returns an array of HeartBeat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * @param $d_id
     * @param $from
     * @param $to
     * @return HeartBeat[]
     */

    public function findByBucket($d_id,$from,$to): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.collect_time ,p.heart_beat_value')
            ->andWhere('p.device_id = :d_id')
            ->andWhere(' p.collect_time >= :from ')
            ->andWhere(' p.collect_time <= :to ')
            ->setParameter('d_id',$d_id)
            ->setParameter('from', $from)
            ->setParameter('to', $to )
            ->orderBy('p.collect_time', 'ASC')
            ->setMaxResults(6000)
            ->getQuery()
            ->getResult();
    }
    
    /*
    public function findOneBySomeField($value): ?HeartBeat
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
