<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{   
    /**
     * @Route("/",name="welcome_page")
     */
    public function home()
    {
        return $this->render('login/welcome.html.twig');
    }

    /**
     * @Route("/login",name="User_login")
     * 
     */
    public function login(){

        return $this->render('dashboard/login.html.twig');
    }

    /**
     * @Route("/logout",name="User_logout")
     */

    public function logout(){
        
    }

    /**
     * @Route("/profil/{id}", name="account_profile",requirements={"id":"\d+"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param $id
     * @return Response
     * @IsGranted ("ROLE_USER")
     */
    public function Profile(Request $request,UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager,$id): Response
    {
        $user=$manager->getRepository(User::class)->find($id);
        $role=$user->getRoles();
        $info['randezvous']=count($user->getRandezvouses()->toArray());
        $info['complaint']=count($user->getComplaints()->toArray());
        $info['doctor']=$user->getPatients()->first();
        $sensors=$user->getSensorGateway();
        $avatar=$user->getAvatar();
        $user->setAvatar(null);
            $form=$this->createForm(UserFormType::class,$user);
            $form->handleRequest($request);
            $manager=$this->getDoctrine()->getManager();
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
                }else{$user->setAvatar($avatar);}
                // encoder le mot de passe
                $hash=$encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($hash);
                // default values in form
                $manager->flush();
                $this->addFlash(
                    'success',
                    "Votre coordonnées sont mis à jour."
                );
            }
        $user->setAvatar($avatar);
        if (in_array('ROLE_DOCTOR',$role)){
            $info['patients'] = count($user->getDoctor());
            $info['doctor']=null;
            return $this->render('account/profil.html.twig',[
                'user'=>$user,
                'avatar'=>$avatar,
                'info'=>$info,
                'form'=>$form->createView(),
                'errors'=>$errors
            ]);
        }

        elseif (in_array('ROLE_USER',$role) && $sensors==null ){
            return $this->render('account/profil.html.twig',[
                'user'=>$user,
                'avatar'=>$avatar,
                'info'=>$info,
                'form'=>$form->createView(),
                'errors'=>$errors
            ]);
        }

            return $this->render('account/profil-v2.html.twig',[
            'user'=>$user,
            'avatar'=>$avatar,
            'info'=>$info,
            'sensors'=>$sensors,
            'form'=>$form->createView(),
            'errors'=>$errors
        ]);
    }


    /**
     * @Route("/account/{id}/status", name="account_status")
     */
    public function Account_Status($id): Response
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
        $status= !$user->getActive();
        $message= $status ? "Utilisateur a été bien activé" : "Utilisateur a été bien désactivé";
        dump($message);
        $user->setActive($status);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash(
            'success',
            $message
        );
        return $this->redirectToRoute('account_profile',['id'=>$id]);
    }


    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function adminlogin()
    {
        return $this->render('admin/login.html.twig');
    }

    /**
     * @Route("/login/error", name="login_error")
     */
    public function loginError()
    {
        return $this->render('errors/login_error.html.twig');
    }
}
