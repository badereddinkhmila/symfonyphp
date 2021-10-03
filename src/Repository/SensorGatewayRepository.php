<?php

namespace App\Repository;

use App\Entity\SensorGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SensorGateway|null find($id, $lockMode = null, $lockVersion = null)
 * @method SensorGateway|null findOneBy(array $criteria, array $orderBy = null)
 * @method SensorGateway[]    findAll()
 * @method SensorGateway[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SensorGatewayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SensorGateway::class);
    }

    // /**
    //  * @return SensorGateway[] Returns an array of SensorGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SensorGateway
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findCountPerDoc($users_v)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql2="select to_char ( sg.deploy_date , 'dd-mm-yyyy' ),  
                count (*)  
                from   sensor_gateway sg    
                where(sg.patient_sg_id IN ".$users_v." AND sg.is_Active=true)
                group by to_char ( sg.deploy_date, 'dd-mm-yyyy' );
            ";
        $stmt = $conn->prepare($sql2);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findCountPerDay(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql2="select to_char ( sg.deploy_date , 'dd-mm-yyyy' ),  
                count (*)  
                from   sensor_gateway sg    
                where sg.is_Active=true
                group by to_char ( sg.deploy_date, 'dd-mm-yyyy' );
            ";
        $stmt = $conn->prepare($sql2);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
