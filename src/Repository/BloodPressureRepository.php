<?php

namespace App\Repository;

use App\Entity\BloodPressure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BloodPressure|null find($id, $lockMode = null, $lockVersion = null)
 * @method BloodPressure|null findOneBy(array $criteria, array $orderBy = null)
 * @method BloodPressure[]    findAll()
 * @method BloodPressure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BloodPressureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BloodPressure::class);
    }

    // /**
    //  * @return BloodPressure[] Returns an array of BloodPressure objects
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
     * @return BloodPressure[]
     */

    public function findByBucket($d_id,$from,$to): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.collect_time ,p.bp_value')
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
    public function findOneBySomeField($value): ?BloodPressure
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
