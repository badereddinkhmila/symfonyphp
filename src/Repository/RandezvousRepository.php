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
    
    public function findLast()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

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

    public function findCountPerUser($user_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select to_char ( r2.dated_for , 'dd-mm-yyyy' ),  
                count (*)  
                from   randezvous r2
                inner join randezvous_user ru on ru.randezvous_id = r2.id 
                where ru.user_id = ?
                group  by to_char ( r2.dated_for , 'dd-mm-yyyy' );
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $user_id);
            $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findCountPerDay(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select to_char ( r2.created_at , 'dd-mm-yyyy' ),  
                count (*)  
                from   randezvous r2
                group  by to_char ( r2.created_at , 'dd-mm-yyyy' );
            ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function findByUser($user)
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.dated_for)')
            ->groupBy('r.dated_for')
            ->innerJoin('r.parts', 'part')
            ->where('part.id = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findStats($user_id)
    {
        return $this->createQueryBuilder('r')
            ->where('r.parts = :userId')
            ->setParameter('userId',$user_id)
            ->orderBy('r.dated_for')
            ->getQuery()
            ->getResult()
            ;
    }

}
