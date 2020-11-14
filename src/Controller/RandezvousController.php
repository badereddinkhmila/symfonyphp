<?php

namespace App\Controller;


use DateTime;
use DateInterval;
use App\Entity\Randezvous;
use App\Form\RandezvousType;
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
    public function randez_vous_list(RandezvousRepository $rp){
        $rdv=$rp->findAll();
        $rdvs=array();
        $index=0;
        foreach($rdv as $rd){
            $temp = array(
                'id'=>$rd->getId(),
                'title' => $rd->getType(),
                'description'=>$rd->getDescription(),
                'start'=>$rd->getDatedFor()->format('Y-m-d H:i:s'),
                'backgroundColor'=>$rd->getColor(),
                'color'=>$rd->getColor(),
                'end'=>$rd->getEndIn()->format('Y-m-d H:i:s')
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
        else{$usr=$rd->getParts()->first();
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

    /**
     * @Route("/new", name="randezvous_new", methods={"GET","POST"})
     */
    public function new(Request $request,RoleRepository $rp,RandezvousRepository $rdvrepo,UserRepository $urp): Response
    {   
        $randezvous = new Randezvous();
        $entityManager=$this->getDoctrine()->getManager();
        $appuser=$this->getUser();
        if ($request->isXmlHttpRequest() && $_SERVER['REQUEST_METHOD'] =='POST') {
            $resp=json_decode($request->getContent(),true);
            $color=$resp[0];
            $start=new DateTime($resp[2]);
            $randezvous->setDatedFor($start)
                      ->setType($resp[1])
                      ->setDescription("")
                      ->setIsValid(true)
                      ->setColor($color);
            $endin=new DateTime($resp[2]);
            $endin->add(new DateInterval('PT45M'));
            $randezvous->setEndIn($endin);
            $entityManager->persist($randezvous);
            $entityManager->flush();
        }
        return new Response();
    }

    /**
     * @Route("/newe", name="randezvous_pt2", methods={"GET","POST"})
     */
    public function Part2(Request $request,RoleRepository $rp,RandezvousRepository $repo,UserRepository $repository): Response
    {   
        $entityManager=$this->getDoctrine()->getManager();
        $appuser=$this->getUser();
        $rdv=($repo->findLast())[0];
        $role = $rp->find(2);
        $options=[];
        if($appuser->getUserRoles()->contains($role))
        { $pats=$appuser->getDoctor();
           $options['choi']=$pats;
           $options['name']='Patients';
           $options['state']=false;
        }                 
        else{
            $doc=$appuser->getPatients();
            $options['choi']=$doc;
            $options['state']=true;
        }
    $options['action']=$this->generateUrl('randezvous_pt2');
    $options['method']='POST';
    $form = $this->createForm(RandezvousType::class,$rdv,$options);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        $id=$form->get('parts')->getViewData()[0];
        $part=$repository->find($id);
        $rdv->addPart($part)->addPart($appuser);
        $entityManager->flush();
        return $this->redirectToRoute('randez_vous');
    }
    return $this->render('randezvous/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="randezvous_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,RoleRepository $rp,RandezvousRepository $repo,$id): Response
    {   $user=$this->getUser();
        $randezvous=$repo->find($id);
        $form=null;
        $role = $rp->find(2);
                if($user->getUserRoles()->contains($role))
                {  $pat=$user->getDoctor();
                   $form = $this->createForm(RandezvousType::class,$randezvous,[
                        'choi'=>$pat,
                        'name'=>'Patients',
                        'state'=>false,
                   ]); 
                }                   
                else{
                    $doc=$user->getPatients();
                    $form = $this->createForm(RandezvousType::class,$randezvous,[
                        'choi'=>$doc,
                        'state'=>true
                    ]);
                }
        if($request->isXmlHttpRequest() && $_SERVER['REQUEST_METHOD'] =='GET'){        
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $part=$form->get('parts')->getData();
                dump($part);
                $randezvous->addPart($part);
                $this->getDoctrine()->getManager()->flush();
            }
            return $this->render('randezvous/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/{id}", name="randezvous_delete", methods={"DELETE"})
     */

    public function delete(RandezvousRepository $repo,$id)
    {   $randezvous=$repo->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($randezvous);
            $entityManager->flush();
            $this->addFlash(
                'warning',
                "Le randez-vous a bien été annulée"
            );
            return $this->redirectToRoute('randezvous_list');
    }

     /**
     * @Route("/test", name="test")
     */

    public function test(UserRepository $repo,RandezvousRepository $rdvrep)
    {   
        $pat=$repo->find(37);
        $doc=$repo->find(28);
        $pats=$doc->getDoctor()->toArray();
        $doctor=$pat->getPatients()->toArray();
            return $this->render('randezvous/test.html.twig',[
                'pats'=>$pats,

                'doctor'=>$doctor,
            ]);
    }
        

}
