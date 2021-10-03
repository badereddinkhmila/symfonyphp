<?php

namespace App\Controller;

use App\Entity\Complaint;
use App\Entity\Randezvous;
use App\Entity\SensorGateway;
use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\ComplaintRepository;
use App\Repository\RandezvousRepository;
use App\Repository\ResetPasswordRequestRepository;
use App\Repository\RoleRepository;
use App\Repository\SensorGatewayRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 *@IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{


    /**
     * @Route("/home",name="admin_home")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminHome(Request $request)
    {
        $idx=0;
        $complaint=$this->getDoctrine()->getRepository(Complaint::class)->findAll();
        $users=$this->getDoctrine()->getRepository(User::class)->findAll();
        $sensors=$this->getDoctrine()->getRepository(SensorGateway::class)->findAll();

        $len_comp=count($complaint);
        $len_usr=count($users);
        $len_sg=count($sensors);
        $len_rdv=count($this->getDoctrine()->getRepository(Randezvous::class)->findAll());    

        $complaint=array_slice($complaint,$len_comp-5);
        $users=array_slice($users,$len_usr-5);
        $sensors=array_slice($sensors,$len_sg-5);

        $Complaints=array();
        $Users=array();
        $Sensors=array();
        foreach($users as $usr ){
            $temp = array(
                'date'=>$usr->getCreatedAt()->format('Y-m-d'),
                'person'=>$usr->getAvatar(),
                'fname'=>$usr->getFirstname(),
                'lname'=>$usr->getLastname(),
                'role'=>$usr->getUserRoles()->first()->getTitle()
            );
            $Users[$idx++]=$temp;
        }

        foreach($sensors as $sg ){
            $usr=$sg->getPatientSg();
            $temp = array(
                'person'=>$usr->getAvatar(),
                'fname'=>$usr->getFirstname(),
                'lname'=>$usr->getLastname(),
                'sg_name'=>$sg->getSensorGatewayId(),
                'date'=>$sg->getDeployDate()->format('Y-m-d'),
                'state'=>$sg->getIsActive()
            );
            $Sensors[$idx++]=$temp;
        }

        foreach($complaint as $cp ){
            $temp = array(
                'type'=>$cp->getComplaintType(),
                'start'=>$cp->getCreatedAt()->format('Y-m-d'),
                'status'=>$cp->getIsTreated(),
                'person'=>$cp->getComplaintCreator()->getAvatar(),
                'fname'=>$cp->getComplaintCreator()->getFirstname(),
                'lname'=>$cp->getComplaintCreator()->getLastname()
            );
            $Complaints[$idx++]=$temp;
        }

        return $this->render('admin/home.html.twig',[
            'comps'=>$Complaints,
            'users'=>$Users,
            'sensors'=>$Sensors,
            'count_comp'=>$len_comp,
            'count_rdv'=>$len_rdv,
            'count_users'=>$len_usr,
            'count_sg'=>$len_sg,
        ]);
    }

    /**
     * @Route("/stats",name="admin_stats")
     * @param Request $request
     * @param UserRepository $urp
     * @param SensorGatewayRepository $sgrp
     * @param ComplaintRepository $cmprp
     * @param RandezvousRepository $rdvrp
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminStats(Request $request,UserRepository $urp,SensorGatewayRepository $sgrp,ComplaintRepository $cmprp,RandezvousRepository $rdvrp)
    {
        $stats['users_age']=array_map(function($val){
            return array_values($val);
        },$urp->findCountPerAge());
        $stats['users_growth']=array_map(function($val){
            $tmsmp=new DateTime($val['to_char']);
            $val['to_char']=$tmsmp->getTimestamp()*1000;
            return array_values($val);
        },$urp->findCountPerDay());
        $stats['users_gender']=array_map(function($val){
            return array_values($val);
        },$urp->findCountPerGender());
        $stats['users_role']=array_map(function($val){
            if ($val['title']=='ROLE_ADMIN'){$val['title']='Administrateur';}
            elseif ($val['title']=='ROLE_DOCTOR'){$val['title']='docteur';}
            else{$val['title']='Utilisateur';}
            return array_values($val);
        },$urp->findCountPerRole());
        $stats['sensors_stats']=array_map(function($val){
            $tmsmp=new DateTime($val['to_char']);
            $val['to_char']=$tmsmp->getTimestamp()*1000;
            return array_values($val);
        },$sgrp->findCountPerDay());
        $stats['comp_stats']=array_map(function($val){
            $tmsmp=new DateTime($val['to_char']);
            $val['to_char']=$tmsmp->getTimestamp()*1000;
            return array_values($val);
        },$cmprp->findCountPerDay());
        $stats['rdv_stats']=array_map(function($val){
            $tmsmp=new DateTime($val['to_char']);
            $val['to_char']=$tmsmp->getTimestamp()*1000;
            return array_values($val);
        },$rdvrp->findCountPerDay());
        return new JsonResponse(json_encode($stats));
    }

    /**
     * @Route("/monitoring/cassandra",name="admin_cassandra")
     */
    public function cassandraStats()
    {
        return $this->render('admin/cassandra.html.twig');
    }

    /**
     * @Route("/monitoring/kafka",name="admin_kafka")
     */
    public function kafkaStats()
    {
        return $this->render('admin/kafka.html.twig');
    }
    
    /**
     * @Route("/monitoring/potgresql",name="admin_postgresql")
     */
    public function pgStats()
    {
        return $this->render('admin/postgresdatabase.html.twig');
    }
    /**
     * @Route("/monitoring/mqtt",name="admin_mqtt")
     */
    public function mqttStats()
    {
        return $this->render('admin/EMQX.html.twig');
    }

    /**
     * @Route("/monitoring/webserver",name="admin_webserver")
     */
    public function webserverStats()
    {
        return $this->render('admin/hostingmachine.html.twig');
    }


    /**
     * @Route("/json/doctors",name="json_doctors",methods={"GET"})
     * @param UserRepository $repository
     * @return JsonResponse
     */
    public function Doctors(UserRepository $repository)
    {
        $doctors = $repository->findByDoctor(true);
        $jsonData = array();
        $idx = 0;
        foreach ($doctors as $pt) {
            $temp = [
                'id' => $pt->getId(),
                'firstname' => $pt->getFirstname(),
                'lastname' => $pt->getLastname(),
                'avatar' => $pt->getAvatar(),
                'email' => $pt->getEmail(),
            ];
            $jsonData[$idx++] = $temp;
        }
        return new JsonResponse($jsonData);
    }

    /**
     * @Route("/docteurs",name="doctors_list")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param RoleRepository $rp
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function manageDoctors(Request $request, UserPasswordEncoderInterface $encoder, RoleRepository $rp)
    {

        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        $manager = $this->getDoctrine()->getManager();
        // set default role as doctor
        $role = $rp->find(2);
        $errors=false;
        if ($form->isSubmitted() && !$form->isValid()) {
            $errors=true;
        }
        if ($form->isSubmitted() && $form->isValid()) {
            //upload d'image
            $errors=false;
            if (!empty($user->getAvatar())) {
                $image = $form->get('avatar')->getData();
                $fichier = " /uploads/" . md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fichier);
                $user->setAvatar($fichier);
            }
            // encoder le mot de passe
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            // default values in form
            $user->setIsDoctor(true);
            $user->addUserRole($role);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Le Docteur <strong>{$user->getFirstname()} {$user->getLastname()}</strong> a bien été crée"
            );
            return $this->redirectToRoute('doctors_list');
        }

        return $this->render('/admin/users/Doctorslist.html.twig', [
            'form' => $form->createView(),
            'errors'=>$errors
        ]);
    }

    /**
     * @Route("/docteurs/{id}/delete",name="delete_doctor")
     * @param $id
     * @param EntityManagerInterface $manager
     * @param UserRepository $rp
     * @return RedirectResponse
     */
    public function deletePatient($id,EntityManagerInterface $manager,UserRepository $rp,ResetPasswordRequestRepository $pwdrp){
        $user=$rp->find($id);
        $manager->remove($user);
        $manager->flush();
        $this->addFlash(
            'danger',
            "Le docteurs <strong>{$user->getFirstname()} {$user->getLastname()}</strong> a bien été supprimée"
        );
        return $this->redirectToRoute('doctors_list');
    }

}
