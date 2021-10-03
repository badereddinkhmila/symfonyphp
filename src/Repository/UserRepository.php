<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param $value
     * @return array
     */
    public function findByDoctor($value): array
    {   

        return $this->createQueryBuilder('u')
            ->andWhere('u.isDoctor = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findCountPerDay(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select to_char ( au.created_at , 'dd-mm-yyyy' ),  
                count (*)  
                from   app_users au 
                group  by to_char ( au.created_at , 'dd-mm-yyyy' );
            ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function findCountPerDayPerDoc($doc_id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select to_char ( au.created_at , 'dd-mm-yyyy' ), count (p.patient_id)
                from   app_users au inner join patients p on au.id = p.patient_id
                where p.doctor_id = ".$doc_id."
                group  by to_char ( au.created_at , 'dd-mm-yyyy' );
            ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function findCountPerGender(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select au.gender,count (*)
	            from   app_users au 
                group  by au.gender;
               ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findCountPerAge(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select COUNT(*),
 	            CASE
  		            WHEN au.age <18 THEN 'Under 18'
  		            WHEN au.age BETWEEN 18 AND 40 THEN '18-40'
  		            WHEN au.age BETWEEN 41 AND 62 THEN '41-62'
  		            WHEN au.age >45 THEN 'Over 63'
	            END AS age_range 
                FROM app_users au
                GROUP BY age_range;
               ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findCountPerRole(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select r.title, count (*)  
                from   role_user ru
                inner join role r on r.id = ru.role_id 
                group  by (r.title);";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
