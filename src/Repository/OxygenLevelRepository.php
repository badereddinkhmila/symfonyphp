<?php

namespace App\Repository;

use App\Entity\OxygenLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OxygenLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method OxygenLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method OxygenLevel[]    findAll()
 * @method OxygenLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OxygenLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OxygenLevel::class);
    }

    // /**
    //  * @return OxygenLevel[] Returns an array of OxygenLevel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
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
     * @return OxygenLevel[]
     */

    public function findByBucket($d_id,$from,$to): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.collect_time ,p.pulse,p.spo2')
            ->andWhere('p.device_id = :d_id')
            ->andWhere(' p.collect_time >= :from ')
            ->andWhere(' p.collect_time <= :to ')
            ->setParameter('d_id',$d_id)
            ->setParameter('from', $from)
            ->setParameter('to', $to )
            ->orderBy('p.collect_time', 'ASC')
            //->setMaxResults(6000)
            ->getQuery()
            ->getResult();
    }

    public function findMaxMinByBucket($d_id,$from,$to): array
    {
        return $this->createQueryBuilder('p')
            ->select('Max(p.spo2) AS max_v, Min(p.spo2) AS min_v')
            ->andWhere('p.device_id = :d_id')
            ->andWhere(' p.collect_time >= :from ')
            ->andWhere(' p.collect_time <= :to ')
            ->setParameter('d_id',$d_id)
            ->setParameter('from', $from)
            ->setParameter('to', $to )
            ->getQuery()
            ->getResult();
    }
    /*
    public function findOneBySomeField($value): ?OxygenLevel
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
