<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class DoctorController extends AbstractController
{
    /**
     * @Route("/dashboard/home",name="dash_home")
     * @IsGranted("ROLE_USER")
     */

    public function home() {
        return $this->render('dashboard/home.html.twig');
    }

    /**
     * @Route("/dashboard/patients/{page?1}",name="doctor_dashboard",requirements={"page":"\d+"})
     * @IsGranted("ROLE_DOCTOR")
     */
    public function Patients(UserRepository $repo,PaginationService $ps,Request $request,UserPasswordEncoderInterface $encoder,RoleRepository $rp,$page){

        $doc=$this->getUser();
            
            $user=new User();
            $form=$this->createForm(UserFormType::class,$user);
            $form->handleRequest($request);
            $manager=$this->getDoctrine()->getManager();
            // set default role as doctor 
            $role = $rp->findRole(3);
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
                $user->addUserRole($role[0]);
                $user->addPatient($doc);
                $user->setCreatedAt(new \DateTime());
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "Le Patient <strong>{$user->getFirstname()} {$user->getLastname()}</strong> a bien été crée"
                );
                return $this->redirectToRoute('doctor_dashboard');}


        // pagination
        $ps->setEntityClass(User::class)
           ->setPage($page)
           ->setLimit(10);
        //view
      return $this->render('dashboard/patientlist.html.twig',[
            'users'=>$doc->getPatients(),
            'form'=>$form->createView(),
        ]);  
    }
    
    /**
     * @Route("/dashboard/patients/{id?}",name="patient_profile",requirements={"page":"\d+"})
     * @IsGranted("ROLE_DOCTOR")
     */
    public function Patient(UserRepository $repo ,$id){
            
        return $this->render('dashboard/profil.html.twig',[
              'user'=>$repo->find($id),
          ]);  
      }

     /**
     * @Route("/dashboard/patients/{id?}/data",name="patient_data",requirements={"page":"\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function PatientData(Request $request,UserRepository $repo ,$id){

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) { 
            
            $user=$repo->find($id);
            $data=$user->getPatientdatas();
            
            $jsonData = array();  
            $idx = 0;
            foreach($data as $dt) {  
               $temp = array(
                  'tension' => $dt->getTension(),  
                  'oxygene' => $dt->getOxygene(),
                  'glycose'=>$dt->getGlucose(),
                  'poids' => $dt->getPoids(),  
                  'temperature' => $dt->getTemperature(),
                  'gateway'=>$dt->getGatewayid(),
                  'date'=>$dt->getCreatedAt()    
               );   
               $jsonData[$idx++] = $temp;  
            } 
            return new JsonResponse($jsonData); 
         }
          

        return $this->render('dashboard/data.html.twig',[
              'id'=>$id,
          ]);  
      }


}

