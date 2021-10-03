<?php

namespace App\Controller;

use App\Entity\SensorGateway;
use App\Form\SensorsType;
use App\Repository\SensorGatewayRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/dashboard/sensor/gateway")
 */
class SensorGateController extends AbstractController
{
    /**
     * @Route("/", name="sensor_gateway")
     * @param SensorGatewayRepository $rp
     * @return Response
     */
    public function index(SensorGatewayRepository $rp,UserRepository $urp): Response
    {
        return $this->render('sensor_gate/index.html.twig', [
            ]);
    }


    /**
     * @Route("/list", name="sensor_gateway_list")
     * @param SensorGatewayRepository $sgrp
     * @return JsonResponse
     */
    public function List(SensorGatewayRepository $sgrp): JsonResponse
    {   $data=array();
        $sensors=new ArrayCollection();
        function tri_data($data,$treated_data): array
        {
                $idx=0;
                foreach ($treated_data as $dt){
                    dump($dt->getDeployDate());
                    $user=$dt->getPatientSg();
                    $doc=($user->getPatients())->first();
                        $temp=array(
                            'id'=>$dt->getSensorGatewayId(),
                            'gly_sensor'=>$dt->getGlycoseSensor(),
                            'temp_sensor'=>$dt->getTemperatureSensor(),
                            'oxy_sensor'=>$dt->getOxygeneSensor(),
                            'bp_sensor'=>$dt->getBpSensor(),
                            'weight_sensor'=>$dt->getWeightSensor(),
                            'state'=>$dt->getIsActive(),
                            'user_id'=>$user->getId(),
                            'user_f_name'=>$user->getFirstname(),
                            'user_l_name'=>$user->getLastname(),
                            'user_photo'=>$user->getAvatar(),
                        );

                    $data[$idx++]=$temp;
                }
            return $data;
        }
        if($this->isGranted('ROLE_ADMIN')){
            $sensors=$sgrp->findAll();
            $data=tri_data($data,$sensors);
        }elseif($this->isGranted('ROLE_DOCTOR')){
            $patients=$this->getUser()->getDoctor();
            foreach ($patients as $pt){
                if (!empty($pt->getSensorGateway())){
                   $sensors->add($pt->getSensorGateway());
                }
            }
            $data=tri_data($data,$sensors);
        }else{
            if(!empty($this->getUser()->getSensorGateway())){
                $sensors->add($this->getUser()->getSensorGateway());
            }
            $data=tri_data($data,$sensors);
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/{id}/edit", name="sensor_gateway_edit")
     * @param SensorGatewayRepository $rp
     * @return Response
     */
    public function edit(Request $request,SensorGatewayRepository $rp,UserRepository $urp,EntityManagerInterface $manager,$id): Response
    {   $sensor=$rp->find($id);
        $options=[];
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $users=$urp->findAll();
            $options['choi']=$users;
        }
        elseif($this->isGranted('ROLE_DOCTOR')){
            $users=$this->getUser()->getDoctor();
            $options['choi']=$users;
        }
        else{
            return $this->render('errors/403.html.twig');
        }

        $form = $this->createForm(SensorsType::class,$sensor,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sensor->setDeployDate(new \DateTime());
            $manager->flush();
            $this->addFlash(
                'success',
                "La modification a réussi"
            );
            return $this->redirectToRoute('sensor_gateway');
        }
        return $this->render('sensor_gate/update.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/new", name="sensor_gateway_new")
     * @param SensorGatewayRepository $rp
     * @return Response
     */
    public function new(Request $request, SensorGatewayRepository $rp,UserRepository $urp, EntityManagerInterface $manager): Response
    {
        $sensor=new SensorGateway();
        $user=$this->getUser();
        $options=[];
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $users=$urp->findAll();
            $options['choi']=$users;
        }
        elseif($this->isGranted('ROLE_DOCTOR')){
            $users=$this->getUser()->getDoctor();
            $options['choi']=$users;
        }
        $form = $this->createForm(SensorsType::class,$sensor,$options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            $manager->persist($sensor);
            $manager->flush();
            $this->addFlash(
                'success',
                "Le Patient <strong>{$sensor->getPatientSg()->getFirstname()}</strong> s'est vu attribuer des équipements."
            );
            return $this->redirectToRoute('sensor_gateway');
        }

        return $this->render('sensor_gate/form.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/{id}/delete", name="sensor_gateway_delete")
     * @param SensorGatewayRepository $rp
     * @return Response
     */
    public function delete(Request $request,SensorGatewayRepository $rp,EntityManagerInterface $manager,$id): Response
    {   $sensor=$rp->find($id);
        if ($this->isGranted('ROLE_ADMIN') Or $this->isGranted('ROLE_DOCTOR'))
        {
            $manager->remove($sensor);
            $manager->flush();
            $this->addFlash(
                'danger',
                "Le paquet a été bien supprimer."
            );
            return $this->redirectToRoute('sensor_gateway');
        }
        else{
            return $this->render('errors/403.html.twig');
        }
    }

}
