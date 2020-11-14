<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Mercure\Cookies\CookieGenerator;
use App\Messages\message\IotMessage;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PatientdataRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class DoctorController
 * @package App\Controller
 * @Route ("/dashboard")
 * @IsGranted("ROLE_USER")
 */
class DoctorController extends AbstractController
{
    /**
     * @Route("/",name="dash_home")
     * @return Response
     */
    public function home(): Response
    {
        return  $this->render('dashboard/home.html.twig', []);
    }


    /**
     * @Route("/patients",name="doctor_dashboard")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param RoleRepository $rp
     * @return JsonResponse|RedirectResponse|Response
     */
    public function Patients(Request $request,UserPasswordEncoderInterface $encoder,RoleRepository $rp){

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
     * @Route("/patients/{id}/delete",name="delete_patient")
     * @param $id
     * @param EntityManagerInterface $manager
     * @param UserRepository $rp
     * @return RedirectResponse
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
     * @Route("/patients/map",name="map_patient")
     */
    public function mapPatient(){
        return $this->render('/dashboard/map.html.twig');

        
    }

    /**
     * @Route("/patient/{id?}",name="patient_profile",requirements={"page":"\d+"})
     * @param UserRepository $repo
     * @param $id
     * @return Response
     */
    public function Patient(UserRepository $repo ,$id){
            
        return $this->render('dashboard/profil.html.twig',[
              'user'=>$repo->find($id),
          ]);  
    
    }

    /**
     * @Route("/patients/{id?}/data",name="patient_data",requirements={"id":"\d+"})
     * @param Request $request
     * @param UserRepository $repo
     * @param PatientdataRepository $rp
     * @param EntityManagerInterface $em
     * @param $id
     * @return JsonResponse|Response
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


    /**
     * @Route ("/ssedata" , name="sse_stream")
     * @param CookieGenerator $cookieGenerator
     * @return Response
     */
    public function SSE_stream (CookieGenerator $cookieGenerator){
        $response=$this->render('dashboard/sse.html.twig');
        $response->headers->setCookie($cookieGenerator->generate());
        return $response;
    }



}