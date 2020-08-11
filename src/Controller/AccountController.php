<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("dashboard/account/{id}", name="account_profile",requirements={"id":"\d+"})
     */
    public function profil(Request $req,EntityManagerInterface $manager,$id)
    {   
        $user=$manager->getRepository(User::class)->find($id);

        return $this->render('account/profil.html.twig',['user'=>$user]);
    
    }

}
