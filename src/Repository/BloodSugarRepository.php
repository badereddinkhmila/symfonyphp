<?php

namespace App\Repository;

use App\Entity\BloodSugar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BloodSugar|null find($id, $lockMode = null, $lockVersion = null)
 * @method BloodSugar|null findOneBy(array $criteria, array $orderBy = null)
 * @method BloodSugar[]    findAll()
 * @method BloodSugar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BloodSugarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BloodSugar::class);
    }

    // /**
    //  * @return BloodSugar[] Returns an array of BloodSugar objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
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
     * @return BloodSugar[]
     */

    public function findByBucket($d_id,$from,$to): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.collect_time ,p.mg_dl,p.mmol_l')
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
        return $this->createQueryBuilder('p')
            ->select('Max(p.mg_dl) AS max_v, Min(p.mg_dl) AS min_v')
            ->andWhere('p.device_id = :d_id')
            ->andWhere(' p.collect_time >= :from ')
            ->andWhere(' p.collect_time <= :to ')
            ->setParameter('d_id',$d_id)
            ->setParameter('from', $from)
            ->setParameter('to', $to )
            ->getQuery()
            ->getResult();
    }
}
