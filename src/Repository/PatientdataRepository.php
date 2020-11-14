<?php

namespace App\Repository;

use App\Entity\Patientdata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Patientdata|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patientdata|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patientdata[]    findAll()
 * @method Patientdata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientdataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patientdata::class);
    }

    // /**
    //  * @return Patientdata[] Returns an array of Patientdata objects
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
    public function findOneBySomeField($value): ?Patientdata
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    
    public function findLast($patie): array
    {
        return $this->createQueryBuilder('patientdata')
            ->andWhere('p.personne = :pers')
            ->setParameter('pers',$patie)
            ->orderBy('patientdata.id','DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByPersonne($patie): array
    {
        return $this->createQueryBuilder('patientdata')
            ->andWhere('patientdata.personne = :pers')
            ->setParameter('pers',$patie)
            ->orderBy('patientdata.id','DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findOneByRow($val,$patie): array
    {
        $qb=$this->createQueryBuilder('p')
            ->where('p.id > :param')
            ->andWhere('p.personne = :pers')
            ->setParameter('pers',$patie)
            ->setParameter('param',$val)
            ->getQuery();
            return $qb->execute();
        ;
    }
    

   /* public function findOneByLast(): ?Patientdata
    {
        return $this->createQueryBuilder('p')
        ->add('select', 'p')
        ->add('from', 'Patientdata p')
        ->add('orderBy', 'p.id DESC')
        
        ->getQuery()
        ->getResult();
    }*/


}
