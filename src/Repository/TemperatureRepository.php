<?php

namespace App\Repository;

use App\Entity\Temperature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Temperature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Temperature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Temperature[]    findAll()
 * @method Temperature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemperatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Temperature::class);
    }

    // /**
    //  * @return Temperature[] Returns an array of Temperature objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
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
     * @return Temperature[]
     */

    public function findByBucket($d_id,$from,$to): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.collect_time ,p.temperature')
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
    public function findMaxMinByBucket($d_id,$from,$to): array
    {
        return $this->createQueryBuilder('t')
            ->select('MAX(t.temperature) AS max_v ,MIN(t.temperature) AS min_v')
            ->andWhere('t.device_id = :d_id')
            ->andWhere(' t.collect_time >= :from ')
            ->andWhere(' t.collect_time <= :to ')
            ->setParameter('d_id',$d_id)
            ->setParameter('from', $from)
            ->setParameter('to', $to )
            ->getQuery()
            ->getOneOrNullResult();
    }
    /*
    public function findOneBySomeField($value): ?Temperature
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
