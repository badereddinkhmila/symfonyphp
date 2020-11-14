<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class PaginationService {

    private $entityClass;
    private $limit=10;
    private $currentPage=1;
    private $manager;

    public function __construct(EntityManagerInterface $manager)
     {
         $this->manager=$manager;
     } 

    public function getPages()
    {   
        $total=count($this->manager->getRepository($this->entityClass)->findAll());

        return ceil($total / $this->limit);
    }

    public function getData(){
        $offset = $this->currentPage * $this->limit - $this->limit;

        $repo=$this->manager->getRepository($this->entityClass);

        $data=$repo->findBy([],[],$this->limit,$offset);

            return $data;
    }
    
    public function setPage($page) {

        $this->currentPage=$page;

        return $this;
    }

    public function getPage() {

       return $this->currentPage;
  
    }

    public function setEntityClass($entityClass) {

        $this->entityClass= $entityClass ;

        return $this;
    }

    public function getEntityClass(){

        return $this->entityClass;
    }

    public function setLimit($limit) {

        $this->limit= $limit ;

        return $this;
    }

    public function getLimit(){

        return $this->limit;
    }
}