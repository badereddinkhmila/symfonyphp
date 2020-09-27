<?php

namespace App\Controller;

use dump;
use DateTime;
use DateInterval;
use App\Entity\User;
use DateTimeInterface;
use App\Entity\Randezvous;
use App\Form\RandezvousType;
use App\Repository\RoleRepository;
use App\Repository\RandezvousRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/randezvous")
 */
class RandezvousController extends AbstractController
{
   
    /**
     * @Route("/new", name="randezvous_new", methods={"GET","POST"})
     */
    public function new(Request $request,RoleRepository $rp,RandezvousRepository $rdvrepo): Response
    {   
        $randezvous = new Randezvous();
        $id=0;
        $appuser=$this->getUser();
        if ($request->isXmlHttpRequest() && $_SERVER['REQUEST_METHOD'] =='POST') {
            $resp=json_decode($request->getContent(),true);
            $color=$resp[0];
            $start=new DateTime($resp[2]);
            $randezvous->setDatedFor($start)
                      ->setDescription($resp[1])
                      ->setIsValid(true)
                      ->setColor($color)
                      ->addPart($appuser);
            $endin=new DateTime($resp[2]);
            $endin->add(new DateInterval('PT45M'));
            $randezvous->setEndIn($endin);                
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($randezvous);
                    $entityManager->flush();  
        }
        
        if($request->isXmlHttpRequest() && $_SERVER['REQUEST_METHOD'] =='GET'){
            $rdv=($rdvrepo->findLast())[0];
            $form = $this->createForm(RandezvousType::class, $rdv);
                $role = $rp->find(2);
                if($appuser->getUserRoles()->contains($role))
                {  $pat=$appuser->getDoctor()->toArray();
                    $form->add('parts',ChoiceType::class, [
                        'label'=>'Patient',
                        'choices'=>$pat,
                        'choice_label' => function ($choice, $key, $value) {
                            return $choice->getFirstname()." ".$choice->getLastname();
                        },
                    ]);
                }                   
                else{
                    $doc=$appuser->getPatients()->toArray();
                    $randezvous->addPart($doc);
                }
            
            if($form->isSubmitted() && $form->isValid()){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($randezvous);
                $entityManager->flush();
            }
            return $this->render('randezvous/new.html.twig', [
                    'form' => $form->createView(),
                ]);
        }
        return new Response();
    }

    /**
     * @Route("/{id}", name="randezvous_delete", methods={"DELETE"})
     */
    
    public function delete(Request $request, Randezvous $randezvou): Response
    {
        if ($this->isCsrfTokenValid('delete'.$randezvou->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($randezvou);
            $entityManager->flush();
        }

        return $this->redirectToRoute('randezvous_index');
    }
    /**
     * @Route("/{id}/edit", name="randezvous_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Randezvous $randezvou): Response
    {
        $form = $this->createForm(RandezvousType::class, $randezvou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('randezvous_index');
        }

        return $this->render('randezvous/edit.html.twig', [
            'randezvou' => $randezvou,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="randezvous_index", methods={"GET"})
     */
    public function index(RandezvousRepository $randezvousRepository): Response
    {
        return $this->render('randezvous/index.html.twig', [
            'randezvouses' => $randezvousRepository->findAll(),
        ]);
    }

}
