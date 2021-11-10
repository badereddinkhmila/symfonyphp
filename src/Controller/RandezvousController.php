<?php

namespace App\Controller;


use DateTime;
use DateInterval;
use App\Entity\Randezvous;
use App\Entity\User;
use App\Form\RandezVousType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\RandezvousRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/dashboard/randezvous")
 * @IsGranted("ROLE_USER")
 */
class RandezvousController extends AbstractController
{
    /**
     * @Route("/",name="randez_vous")
     */
    public function reandez_vous_home(){
        return $this->render('randezvous/index.html.twig');
    }

    /**
     * @Route("/list",name="randezvous_list")
     */
    public function randez_vous_list(Request$request): JsonResponse
    {   
        $appuser=$this->getUser();
        $rdv=$appuser->getRandezvouses();
        $rdvs=array();
        $index=0;
            
        if(!$appuser->getIsDoctor()){
            $dcrdv=$appuser->getPatients()->first()->getRandezvouses();
            foreach($dcrdv as $rd){
                if(!in_array($appuser,$rd->getParts()->toArray())){
                    $temp = array(
                    'start'=>$rd->getDatedFor()->format('Y-m-d H:i:s'),
                    'end'=>$rd->getEndIn()->format('Y-m-d H:i:s'),
                    'display'=>'background',
                    'overlap'=> false,
                    'color'=>'#FFB2B2',
                    'textColor'=> 'black',
                    'editable'=>false,
                    'className'=>"disabled-event", 
                    'droppable'=>false,
                    );
                    $rdvs[$index++] = $temp;    
                }    
        }
        }
        
        foreach($rdv as $rd){
            $temp = array(
                'id'=>$rd->getId(),
                'title' => $rd->getType(),
                'description'=>$rd->getDescription(),
                'start'=>$rd->getDatedFor()->format('Y-m-d H:i:s'),
                'backgroundColor'=>$rd->getColor(),
                'color'=>$rd->getColor(),
                'end'=>$rd->getEndIn()->format('Y-m-d H:i:s'),
                'valid'=>$rd->getIsValid(),
                'overlap'=> false,
                'droppable'=>false,
                'editable'=>false,
                    
            );
            if ($rd->getParts()->first()->getId() == $this->getUser()->getId()) {
                $usr=$rd->getParts()->last();
                $tmp=array(
                    'id'=>$usr->getId(),
                    'firstname'=>$usr->getFirstname(),
                    'lastname'=>$usr->getLastname(),
                    'avatar'=>$usr->getAvatar(),
                );
            }
            else{
                $usr=$rd->getParts()->first();
                $tmp=array(
                    'id'=>$usr->getId(),
                    'firstname'=>$usr->getFirstname(),
                    'lastname'=>$usr->getLastname(),
                    'avatar'=>$usr->getAvatar());
            }
            $rdvs[$index++] = $temp+$tmp;    
        }

        return new JsonResponse($rdvs); 
    }

    public function calc_rdv_end(DateTime $start){
        return $start->add(new DateInterval('PT30M'));     
    }

    /**
     * @Route("/new", name="randezvous_new", methods={"GET","POST"})
     */
    public function new(Request $request)
    {   
        $randezvous = new Randezvous();
        $entityManager=$this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest() && $_SERVER['REQUEST_METHOD'] =='POST') {
            $resp=json_decode($request->getContent(),true);
            $color=$resp[0];
            $start=(new DateTime($resp[2]));
            $randezvous->setDatedFor($start)
                      ->setType($resp[1])
                      ->setIsValid(true)
                      ->setColor($color)
                      ->addPart($this->getUser());
            $randezvous->setEndIn($this->calc_rdv_end((new DateTime($resp[2]))));                        
            $entityManager->persist($randezvous);
            $entityManager->flush();
            $id=$this->getUser()->getRandezvouses()->last()->getId();
            return $this->redirectToRoute('randezvous_pt2',['id'=>$id]);
        }
       
    }

    /**
     * @Route("/new/{id}", name="randezvous_pt2", methods={"GET","POST"})
     */
    public function Part2(Request $request,$id): Response
    {   
        $entityManager=$this->getDoctrine()->getManager();
        $rdv=$this->getDoctrine()->getRepository(Randezvous::class)->find($id);
        $action=$this->generateUrl('randezvous_pt2',['id'=>$id]);
        $form = $this->createForm(RandezVousType::class,$rdv,['action'=>$action]);
        $rdv->setEndIn($rdv->getDatedFor()->add(new DateInterval('PT30M')));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $form->getData()->addPart($this->getUser());
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La consultation a été ajoutée avec succés'
            );
            return $this->redirectToRoute('randez_vous');
        }
        if($form->isSubmitted() && !$form->isValid()){
            return $this->render('randezvous/validate.html.twig', [
                'operation'=>'new',
                'form' => $form->createView(),
            ]);
        }

        return $this->render('randezvous/new.html.twig', [
                'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="randezvous_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,$id)
    {   
        $randezvous=$this->getDoctrine()->getRepository(Randezvous::class)->find($id);
        $form = $this->createForm(RandezVousType::class,$randezvous,
            ['action'=>$this->generateUrl('randezvous_edit',['id'=>$id])]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $start=$form->getData()->getDatedFor();
            $form->getData()->setDatedFor($start);
            $form->getData()->setEndIn($this->calc_rdv_end($start));
            $form->getData()->addPart($this->getUser());
            dump($form->getData());
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'warning',
                "La consultation a bien été modifiée"
            );
            return $this->redirectToRoute('randez_vous'); 
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            dump($form->getData());
            return $this->render('randezvous/validate.html.twig', [
                'operation'=>'edit',
                'form' => $form->createView(),
            ]); 
        }
        return $this->render('randezvous/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="randezvous_delete")
     */

    public function delete($id)
    {       
        $randezvous=$this->getDoctrine()->getRepository(Randezvous::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        if($randezvous !== null){
            $entityManager->remove($randezvous);
            $entityManager->flush();
            $this->addFlash(
                'danger',
                "La consultation a bien été annulée"
            );
            return $this->redirectToRoute("randez_vous");
        }    
    }

     /**
     * @Route("/{id}/test", name="test")
     */

    /* public function test(Request $request,RoleRepository $rp,RandezvousRepository $repo,$id)
    {
        $user=$this->getUser();
        $randezvous=$repo->find($id);
        $form=null;
        $role = $rp->find(2);
        if($user->getUserRoles()->contains($role))
        {  $doctor=$user->getDoctor();
            $form = $this->createForm(RandezVousType::class,$randezvous,[
                'choi'=>$doctor,
                'name'=>'Patients',
                'state'=>true,
            ]);
        }
        else{
            $pats=$user->getPatients();
            $form = $this->createForm(RandezVousType::class,$randezvous,[
                'choi'=>$pats,
                'state'=>true
            ]);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $rdv=$form->getData();
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('randez_vous');
        }

        return $this->render('randezvous/edit.html.twig', [
            'form' => $form->createView(),
            'id'=>$id
        ]);
    }
     */    

}
