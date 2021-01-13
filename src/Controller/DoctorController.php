<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserFormType;
use App\Mercure\Cookies\CookieGenerator;
use App\Repository\ResetPasswordRequestRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TemperatureRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function home(Request $request): Response
    {
        return $this->render('dashboard/home.html.twig', []);
    }


    /**
     * @Route("/patients",name="patient_list")
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
            if ($form->isSubmitted() && !$form->isValid()) {
                return $this->render('dashboard/new.html.twig', [
                    'form' => $form->createView(),]);
            }
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
                return $this->redirectToRoute('patient_list');}

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
    public function deletePatient($id,EntityManagerInterface $manager,UserRepository $rp,ResetPasswordRequestRepository $pwdrp){
        $user=$rp->find($id);
        $pswdreq=$pwdrp->findBySomeUser($user);
        foreach ($pswdreq as $pwdreq){
            $manager->remove($pwdreq);
            $manager->flush();
        }
            $manager->remove($user);
            $manager->flush();
        $this->addFlash(
            'warning',
            "Le Patient <strong>{$user->getFirstname()} {$user->getLastname()}</strong> a bien été supprimée"
        );
        return $this->redirectToRoute('patient_list');
    }

     /**
     * @Route("/map",name="map_patient")
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
     * @Route("/patient/{id?}/data",name="patient_data",requirements={"id":"\d+"})
     * @param CookieGenerator $cookieGenerator
     * @param Request $request
     * @param UserRepository $repo
     * @param $id
     * @return Response
     */
    public function PatientData(CookieGenerator $cookieGenerator,Request $request,UserRepository $repo,$id) {
            $user=$repo->find($id);
            $cookie=$cookieGenerator->generate();
            $response=$this->render('dashboard/data.html.twig',[
                'user'=>$user,
            ]);
            $response->headers->setCookie($cookie);
            return $response;
    }


    /**
     * @Route ("/iot/{id?}" ,methods={"POST","GET"})
     * @param Request $request
     * @param UserRepository $repo
     * @param TemperatureRepository $rp
     * @param $id
     * @return JsonResponse
     */
    public function iot_data (Request $request,UserRepository $repo,TemperatureRepository $rp,$id){
        $user=$repo->find($id);
        $d_id=$user->getGatewayId();
        if ($request->isXmlHttpRequest() && $_SERVER['REQUEST_METHOD'] =='POST') {
            $content=json_decode($request->getContent(),true);
            $jsonResponse = array();
            $to= DateTime::createFromFormat('Y-m-d',$content['to']);
            $from=DateTime::createFromFormat('Y-m-d',$content['from']);
            $data=($rp->findByBucket($d_id,$from,$to));
            foreach($data as $dt){
                array_push($jsonResponse,[($dt['collect_time']->getTimestamp())*1000,$dt['temperature']]);
            }
            return new JsonResponse($jsonResponse);
        }
        $to=(new \DateTime())->format('Y-m-d H:i:s');
        $from=((new \DateTime())->sub(new \DateInterval('P1D')))->format('Y-m-d H:i:s');
        $data=($rp->findByBucket($d_id,$from,$to));
        $json_data=array();
        foreach($data as $dt){
            array_push($json_data,[($dt['collect_time']->getTimestamp())*1000,$dt['temperature']]);
        }

       return new JsonResponse($json_data);

    }
}