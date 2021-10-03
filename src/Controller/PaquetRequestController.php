<?php

namespace App\Controller;

use App\Entity\PaquetRequest;
use App\Form\PaquetRequestType;
use App\Repository\PaquetRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/paquet/request")
 */
class PaquetRequestController extends AbstractController
{
    /**
     * @Route("/", name="paquet_request_index",methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {

        $role=in_array('ROLE_ADMIN',$this->getUser()->getRoles());
        $errors=false;
        $paquetRequest = new PaquetRequest();
        $form = $this->createForm(PaquetRequestType::class, $paquetRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $errors=true;
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $errors=false;
            $paquetRequest->setIssuer($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($paquetRequest);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "La demande a été émise avec succès"
            );
            return $this->redirectToRoute('paquet_request_index');
        }

        return $this->render('paquet_request/index.html.twig', [
            'errors'=>false,
            'form'=>$form->createView(),
            'role'=>$role,
        ]);
    }

    /**
     * @Route("/list/all", name="paquet_request_list_all", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function List_all(PaquetRequestRepository $paquetRequestRepository): Response
    {
        $pq_requests=$paquetRequestRepository->findAll();
        $json_resp=array();
        $idx=0;
        foreach($pq_requests as $pq){
            $temp=array(
                'id'=>$pq->getId(),
                'gateway'=>$pq->getGateway(),
                'glucose'=>$pq->getGlucoseSensor(),
                'oxygen'=>$pq->getOxygenSensor(),
                'bp'=>$pq->getBloodPressureSensor(),
                'temperature'=>$pq->getTemperature(),
                'weight'=>$pq->getWeight(),
                'status'=>$pq->getApproved(),
                'issuer'=>['firstname'=>$pq->getIssuer()->getFirstname(),
                           'lastname'=>$pq->getIssuer()->getLastname(),
                           'avatar'=>$pq->getIssuer()->getAvatar()],
                'created_at'=>$pq->getCreatedAt()->format('Y-m-d'),
            );
            $json_resp[$idx]=$temp;
            $idx++;
        }

        return new JsonResponse($json_resp);
    }

    /**
     * @Route("/list", name="paquet_request_list", methods={"GET"})
     * @IsGranted("ROLE_DOCTOR")
     */
    public function List(PaquetRequestRepository $paquetRequestRepository): Response
    {
        $pq_requests=$this->getUser()->getPaquetRequests();
        $json_resp=array();
        $idx=0;
        foreach($pq_requests as $pq){
            $temp=array(
                'id'=>$pq->getId(),
                'gateway'=>$pq->getGateway(),
                'glucose'=>$pq->getGlucoseSensor(),
                'oxygen'=>$pq->getOxygenSensor(),
                'bp'=>$pq->getBloodPressureSensor(),
                'temperature'=>$pq->getTemperature(),
                'weight'=>$pq->getWeight(),
                'status'=>$pq->getApproved(),
                'issuer'=>['firstname'=>$pq->getIssuer()->getFirstname(),
                    'lastname'=>$pq->getIssuer()->getLastname(),
                    'avatar'=>$pq->getIssuer()->getAvatar()],
                'created_at'=>$pq->getCreatedAt()->format('Y-m-d'),

            );
            $json_resp[$idx]=$temp;
            $idx++;
        }

        return new JsonResponse($json_resp);
    }

    /**
     * @Route("/{id}/edit", name="paquet_request_edit", methods={"GET","POST"})
     * @IsGranted ("ROLE_USER")
     */
    public function edit(Request $request, PaquetRequest $paquetRequest,$id): Response
    {
        $pq=$this->getDoctrine()->getRepository(PaquetRequest::class)->find($id);
        if($this->getUser()->getId() !== $pq->getIssuer()->getId() && !$this->isGranted('ROLE_ADMIN')){
            return $this->render('errors/403.html.twig');
        }
        $disabled=(($this->getUser()->getId() == $pq->getIssuer()->getId()) && $this->isGranted('ROLE_DOCTOR'));
        $options['dis']=!$disabled;
        $form = $this->createForm(PaquetRequestType::class, $pq,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'warning',
                "La demande a été modifiée avec succès"
            );
            return $this->redirectToRoute('paquet_request_index');
        }
        return $this->render('paquet_request/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="paquet_request_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request,$id): Response
    {   $pq=$this->getDoctrine()->getRepository(PaquetRequest::class)->find($id);
        if($this->getUser()->getId() !== $pq->getIssuer()->getId() && !$this->isGranted('ROLE_ADMIN')){
            return $this->render('errors/403.html.twig');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($pq);
        $entityManager->flush();
        $this->addFlash(
                'danger',
                "La demande a été supprimée avec succès"
        );
        return $this->redirectToRoute('paquet_request_index');
    }
}
