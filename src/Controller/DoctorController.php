<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PatientdataRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DoctorController extends AbstractController
{
    /**
     * @Route("/dashboard",name="dash_home")
     * @IsGranted("ROLE_USER")
     */

    public function home() {
       return $this->render('dashboard/home.html.twig'); 
    }

    /**
     * @Route("/dashboard/patients",name="doctor_dashboard")
     * 
     */
    public function Patients(UserRepository $repo,Request $request,UserPasswordEncoderInterface $encoder,RoleRepository $rp){

        if ($request->isXmlHttpRequest() && $_SERVER['REQUEST_METHOD'] =='GET') {
            $patients=$this->getUser()->getDoctor();  
            $jsonData = array();  
            $idx = 0;  
            foreach($patients as $pt) {  
                $temp = array(
                    'id'=>$pt->getId(),
                    'firstname' => $pt->getFirstname(),
                    'lastname'=>$pt->getLastname(),
                    'avatar'=>$pt->getAvatar(),
                    'email'=>$pt->getEmail(),  
                );   
                $jsonData[$idx++] = $temp;  
            } 
            return new JsonResponse($jsonData); 
        }
        
        
        $doc=$this->getUser();
            $user=new User();
            $form=$this->createForm(UserFormType::class,$user);
            $form->handleRequest($request);
            $manager=$this->getDoctrine()->getManager();
            // set default role as doctor 
            $role = $rp->find(3);
            if( $form->isSubmitted() && $form->isValid() ){
                //upload d'image
                if( !empty($user->getAvatar())){
                $image=$form->get('avatar')->getData();
                $fichier=" /uploads/" . md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'),$fichier);
                $user->setAvatar($fichier);
                }
                // encoder le mot de passe
                $hash=$encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($hash);
                // default values in form
                $user->setIsDoctor(false);
                $user->addUserRole($role);
                $user->addPatient($doc);
                $user->setCreatedAt(new \DateTime());
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "Le Patient <strong>{$user->getFirstname()} {$user->getLastname()}</strong> a bien été crée"
                );
                return $this->redirectToRoute('doctor_dashboard');}

        //view
      return $this->render('dashboard/patientlist.html.twig',[
            'users'=>$doc->getDoctor(),
            'form'=>$form->createView(),
        ]);  
    }
    
    /**
     * @Route("/dashboard/patients/{id}/delete",name="delete_patient")
     * 
     */
    public function deletePatient($id,EntityManagerInterface $manager,UserRepository $rp){
        $user=$rp->find($id);
        $manager->remove($user);
        $manager->flush();
        $this->addFlash(
            'warning',
            "Le Patient <strong>{$user->getFirstname()} {$user->getLastname()}</strong> a bien été supprimée"
        );
        return $this->redirectToRoute('doctor_dashboard');

        
    }

     /**
     * @Route("/dashboard/patients/map",name="map_patient")
     * 
     */
    public function mapPatient(){
        return $this->render('/dashboard/map.html.twig');

        
    }

    /**
     * @Route("/dashboard/patient/{id?}",name="patient_profile",requirements={"page":"\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function Patient(UserRepository $repo ,$id){
            
        return $this->render('dashboard/profil.html.twig',[
              'user'=>$repo->find($id),
          ]);  
    
    }

     /**
     * @Route("/dashboard/patients/{id?}/data",name="patient_data",requirements={"id":"\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function PatientData(Request $request,UserRepository $repo,PatientdataRepository $rp ,EntityManagerInterface $em,$id) {
            
                    $user=$repo->find($id);
                    $data=$user->getPatientdatas();   
                
                if ($request->isXmlHttpRequest() && $_SERVER['REQUEST_METHOD'] =='POST')  {  
                    $LastId=json_decode($request->getContent(),true);
                    $last=intval($LastId["lastid"]);   
                    if(true){                            
                        $datas=$rp->findOneByRow($last,$id);
                        if(!empty($datas)){    
                            $jsonData = array();  
                            $idx = 0;  
                            foreach($datas as $dt) {  
                                $temp = array(
                                    'id' => $dt->getId(),  
                                    'tension' => $dt->getTension(),  
                                );   
                                $jsonData[$idx++] = $temp; 
                            }
                            return new JsonResponse($jsonData);
                        }
                        else{
                            return new JsonResponse($datas);
                        }    
                    }
                    else    {
                       return new JsonResponse("bad request");
                    }
                } 

                if ($request->isXmlHttpRequest() && $_SERVER['REQUEST_METHOD'] =='GET') {  
                    $jsonData = array();  
                    $idx = 0;  
                    foreach($data as $dt) {  
                        $temp = array(
                            'id' => $dt->getId(),  
                            'tension' => $dt->getTension(),   
                        );   
                        $jsonData[$idx++] = $temp;  
                    } 
                    return new JsonResponse($jsonData); 
                }

                return $this->render('dashboard/data.html.twig',[
                    'user'=>$user]);            
    }


}