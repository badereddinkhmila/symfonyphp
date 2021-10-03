<?php

namespace App\Repository;

use App\Entity\Complaint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Complaint|null find($id, $lockMode = null, $lockVersion = null)
 * @method Complaint|null findOneBy(array $criteria, array $orderBy = null)
 * @method Complaint[]    findAll()
 * @method Complaint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Complaint::class);
    }

    // /**
    //  * @return Complaint[] Returns an array of Complaint objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findCountPerUser($user_id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select to_char ( c.created_at , 'dd-mm-yyyy' ),  
                count (*)  
                from   complaint c 
                where c.complaint_creator_id = ?
                group  by to_char ( c.created_at , 'dd-mm-yyyy' );
            ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findCountPerDay(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select to_char ( c.created_at , 'dd-mm-yyyy' ),  
                count (*)  
                from   complaint c 
                group  by to_char ( c.created_at , 'dd-mm-yyyy' );
            ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


}
