<?php

namespace App\Controller;

use App\Entity\Randezvous;
use App\Form\RandezVousType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */

class RandezvousController extends AbstractController
{
    /**
     * @Route("/randezvous", name="randez_vous")
     */
    public function create_randezvous(Request $request,ManagerRegistry  $managers)
    {
        $randezvous=new Randezvous();
        $form=$this->createForm(RandezVousType::class,$randezvous);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $manager=$managers->getManager();
            $manager->persist($randezvous);
            $manager->flush();
        }

        return $this->render('randezvous/randezvous.html.twig',[
            'form' =>$form->createView(),
        ]);
    }
}
