<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Complaint;
use App\Entity\Randezvous;
use App\Form\UserFormType;
use App\Service\DataService;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\Mercure\Update;
use App\Repository\ComplaintRepository;
use App\Mercure\Cookies\CookieGenerator;
use App\Repository\RandezvousRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SensorGatewayRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Messages\message\ColdStorageReqMessage;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ResetPasswordRequestRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function __invoke(Request $request,CookieGenerator $cookieGenerator,RandezvousRepository $rdvrp,ComplaintRepository $cmprp): Response
    {   
        
        $user=$this->getUser();
        $idx=0;
        $complaints=$user->getComplaints()->filter(function(Complaint $complaint) {
            return $complaint->getCreatedAt() > date_sub(new DateTime(),new \DateInterval('P3D'));
        });
        $rdv=$user->getRandezvouses()->filter(function(Randezvous $randezvous) {
            return $randezvous->getDatedFor() > new DateTime();
        });
        $countrdv=count($rdv);
        $countcomp=0;
        $rdv=$rdv->slice($countrdv-4);
        $Complaints=array();
        $randezvouses=array()
        ;

        foreach($rdv as $rd ){
                if ( $user->getId() == $rd->getParts()->first()->getId())
                {
                    $part=$rd->getParts()->last() ;
                }
                else{
                    $part=$rd->getParts()->first();
                }
                $temp = array(
                    'type' => trim($rd->getType()),
                    'start'=>$rd->getDatedFor()->format('Y-m-d H:i:s'),
                    'person'=>$part->getAvatar(),
                    'fname'=>$part->getFirstname(),
                    'lname'=>$part->getLastname()
                );
            $randezvouses[$idx++]=$temp;
            }

    function prepareComp($complaints){
        $Complaints=array();
        $idx=0;
        foreach($complaints as $cp ){
            $temp = array(
                'type'=>$cp->getComplaintType(),
                'start'=>$cp->getCreatedAt()->format('Y-m-d H:i:s'),
                'status'=>$cp->getIsTreated(),
                'person'=>$cp->getComplaintCreator()->getAvatar(),
                'fname'=>$cp->getComplaintCreator()->getFirstname(),
                'lname'=>$cp->getComplaintCreator()->getLastname()
            );
            $Complaints[$idx++]=$temp;
        }
        return $Complaints;}

        if($this->IsGranted('ROLE_DOCTOR')){
            $comps=$user->getComplaints()->toArray();
            $patients=count($user->getDoctor());
            $disps=0;
            $Patients=array();
            foreach(($user->getDoctor()) as $pt ){
                $temp=array(
                    'firstname'=>$pt->getFirstname(),
                    'lastname'=>$pt->getLastname(),
                    'date'=>$pt->getcreatedAt()->format('Y-m-d H:i:s'),
                    'avatar'=>$pt->getAvatar(),
                );
                array_push($Patients,$temp);

                if ( $pt->getSensorGateway() != null ) $disps++;

                if(!empty($pt->getComplaints()->toArray()) )
                    $comps=array_merge($comps,$pt->getComplaints()->toArray());
            }

            $comps=array_filter($comps,function($complaint) {
                return $complaint->getCreatedAt() > date_sub(new DateTime(),new \DateInterval('P2D'));
            });

            $Complaints=prepareComp($comps);

            $dispositif=$disps;
            $response=$this->render('dashboard/home_doctor.html.twig', [
                'comps'=>$Complaints,
                'rdvs'=>$randezvouses,
                'pts'=>$Patients,
                'countrdv'=>$countrdv,
                'complaints'=>count($complaints),
                'patients'=>$patients,
                'dispositif'=>$dispositif
            ]);
        }
        else{
            $response=$this->render('dashboard/home_patient.html.twig', [
                'countcomp'=>count($user->getComplaints()),
                'countrdv'=>count($randezvouses),
                'comps' => prepareComp($user->getComplaints()),
                'rdvs' => $randezvouses
            ]);
        }
        $response->headers->setCookie($cookieGenerator->generate());
        return $response;
    }

    /**
     * @Route("/stats",name="dash_stats")
     * @param Request $request
     * @param UserRepository $urp
     * @param SensorGatewayRepository $sgrp
     * @param RandezvousRepository $rdvrp
     * @param ComplaintRepository $cmprp
     * @return JsonResponse
     * @throws \Exception
     */
    public function statistics(Request $request,UserRepository $urp,SensorGatewayRepository $sgrp,RandezvousRepository $rdvrp,ComplaintRepository $cmprp): JsonResponse
    {
        $user=$this->getUser();
        $myresp=array();
        /****** meeting stats *****/
        $rdvss=$rdvrp->findCountPerUser($user->getId());
        $rdvss=array_map(function($val){
            $tmsmp=new DateTime($val['to_char']);
            $resp[0]=$tmsmp->getTimestamp()*1000;
            $resp[1]=$val['count'];
            return $resp;
        },$rdvss);
        /***** complaints stats******/
        $cmpss=$cmprp->findCountPerUser($user->getId());
        $cmpss=array_map(function($val){
            $tmsmp= new DateTime($val['to_char']);
            $resp[0]=$tmsmp->getTimestamp()*1000;
            $resp[1]=$val['count'];
            return $resp;
        },$cmpss);
        $myresp['meeting']=$rdvss;
        $myresp['complaints']=$cmpss;

        if($this->isGranted('ROLE_DOCTOR')){
            $usrs=$urp->findCountPerDayPerDoc($user->getId());
            $usrs=array_map(function($val){
                $tmsmp=new DateTime($val['to_char']);
                $res[0]=$tmsmp->getTimestamp()*1000;
                $res[1]=$val['count'];
                return $res;
            },$usrs);
            $patients=$user->getDoctor();
            $Data = array();
            $idx = 0;
            foreach($patients as $pt) {
                $Data[$idx++] = $pt->getId();;
            }
            $sgss=$sgrp->findCountPerDoc('('.implode(',',$Data).')');
            $sgss=array_map(function($val){
                $tmsmp=new DateTime($val['to_char']);
                $resp[0]=$tmsmp->getTimestamp()*1000;
                $resp[1]=$val['count'];
                return $resp;
            },$sgss);
            $myresp['sensors']=$sgss;
            $myresp['patients']=$usrs;
        }
        return new JsonResponse($myresp);
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
            $errors=false;
            if ($form->isSubmitted() && !$form->isValid()) {
                $errors=true;
            }

            if( $form->isSubmitted() && $form->isValid() ){
                $errors=false;
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
            'errors'=>$errors
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
    public function Profile(UserRepository $repo ,$id){
            
        return $this->render('account/profil.html.twig',[
              'user'=>$repo->find($id),
          ]);  
    
    }

    /**
     * @Route("/patient/{id?}/data",name="patient_data",requirements={"id":"\d+"})
     * @param CookieGenerator $cookieGenerator
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function Patient_Data(CookieGenerator $cookieGenerator,$id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $gateway=null;
        if ($user->getSensorGateway() !== null){
            $gateway = $user->getSensorGateway()->getSensorGatewayId();
        }
            $cookie=$cookieGenerator->generate();
            $response=new Response();
            $response->headers->setCookie($cookie);
            $response->sendHeaders();
            return $this->render('dashboard/data.html.twig',[
                'user'=>$user,
                'gateway'=>$gateway
            ],$response);
    }

    /**
     * @Route ("/iot/{id?}" ,methods={"POST","GET"})
     * @param Request $request
     * @param UserRepository $repo
     * @param $id
     * @param MessageBusInterface $bus
     * @return JsonResponse
     */
    public function IOT_Data (Request $request,UserRepository $repo,$id,MessageBusInterface $bus,DataService $dataService,SessionInterface $session){
        $user=$repo->find($id);
        $d_id=$user->getSensorGateway()->getSensorGatewayId();
        $topic="http://avcdocteur.com/".$d_id."/update"; 
        $limit=new \DateInterval('P30D');
        if ($request->isXmlHttpRequest() && $_SERVER['REQUEST_METHOD'] =='POST') {
            $content=json_decode($request->getContent(),true);
            $jsonResponse = array();
            $to= DateTime::createFromFormat('Y-m-d',$content['to']);
            $from=DateTime::createFromFormat('Y-m-d',$content['from']);
            if($to < $from ){
               $temp=$to;
               $to=$from;
               $from=$temp;
               $from_copie=DateTime::createFromFormat('Y-m-d',$content['to']);
            }else{
                $from_copie=DateTime::createFromFormat('Y-m-d',$content['from']);
            }
            $compare=($from_copie->add($limit))->format('Y-m-d');
            if ((new \DateTime())->format('Y-m-d') > $compare ){
                $req['gateway_id']=$d_id;
                $req['start']=$from;
                $req['end']=$to;
                $bus->dispatch(new ColdStorageReqMessage($req));
            }
            else{
                $jsonResponse['temperature']=$dataService->getIotTemperature($d_id,$from,$to);
                $jsonResponse['weight']=$dataService->getIotweight($d_id,$from,$to);
                $jsonResponse['glucose']=$dataService->getIotGlucose($d_id,$from,$to);
                $jsonResponse['bp']=$dataService->getIotBP($d_id,$from,$to);
                $jsonResponse['oxygen']=$dataService->getIotOxygen($d_id,$from,$to);
                {$update = new Update([$topic], json_encode(
                    $jsonResponse));
                    $bus->dispatch($update);
                }
            }
            return new JsonResponse($jsonResponse);
        }

        $to=(new \DateTime())->format('Y-m-d H:i:s');
        $from=((new \DateTime())->sub(new \DateInterval('P1D')))->format('Y-m-d H:i:s');
        $json_data['temperature']=$dataService->getIotTemperature($d_id,$from,$to);
        $json_data['oxygen']=$dataService->getIotOxygen($d_id,$from,$to);
        $json_data['weight']=$dataService->getIotweight($d_id,$from,$to);
        $json_data['bp']=$dataService->getIotBP($d_id,$from,$to);
        $json_data['glucose']=$dataService->getIotGlucose($d_id,$from,$to);

       return new JsonResponse($json_data);
    }
}