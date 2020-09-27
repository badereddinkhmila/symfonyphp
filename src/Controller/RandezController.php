<?php

namespace App\Controller;

use DateInterval;
use App\Entity\Randezvous;
use App\Form\RandezVousType;
use App\Repository\RandezvousRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */

class RandezController extends AbstractController
{
    /**
     * @Route("/dashboard/randez-vous",name="dash_randez_vous")
     */
    public function randez_vous(){
      
        return $this->render('randezvous/randezvous.html.twig',[
        ]);   
    }

    /**
     * @Route("/dashboard/randez-vous-list",name="dash_randez_vous_list")
     */
    public function randez_vous_list(RandezvousRepository $rp){
        $rdv=$rp->findAll();
        $diff = new DateInterval('PT30M');
        $rdvs=array();
        $index=0;
        foreach($rdv as $rd){
            $temp = array(
                'id'=>$rd->getId(),
                'title' => $rd->getDescription(),
                'start'=>$rd->getDatedFor()->format('Y-m-d H:i:s'),
                'backgroundColor'=>$rd->getColor(),
                'color'=>$rd->getColor(),
                'end'=>$rd->getEndIn()->format('Y-m-d H:i:s')
            );
        if ($rd->getParts()->first()->getId() === $this->getUser()->getId()) {
            $usr=$rd->getParts()->last();
            $tmp=array(
                'id'=>$usr->getId(),
                'firstname'=>$usr->getFirstname(),
                'lastname'=>$usr->getLastname(),
                'avatar'=>$usr->getAvatar(),
            );
        }
        else{$usr=$rd->getParts()->first();
            $tmp=array(
                'id'=>$usr->getId(),
                'firstname'=>$usr->getFirstname(),
                'lastname'=>$usr->getLastname(),
                'avatar'=>$usr->getAvatar(),
            );
        }
            $rdvs[$index++] = $temp+$tmp;    
        }
        return new JsonResponse($rdvs); 
    }
}
