<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/",name="welcome_page")
     */
    public function home()
    {
        return $this->render('login/welcome.html.twig');
    }
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request,ManagerRegistry  $managers,UserPasswordEncoderInterface $encoder)
    {
        $user=new User();
        $form=$this->createForm(NewUserType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager=$managers->getManager();
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('User_login');
        }
        

        return $this->render('login/registration.html.twig', [
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/login",name="User_login")
     * 
     */
    public function login(){

        return $this->render('login/login.html.twig');
    }
}
