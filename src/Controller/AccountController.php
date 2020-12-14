<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{   
    /**
     * @Route("/",name="welcome_page")
     */
    public function home()
    {
        return $this->render('login/welcome.html.twig',[
        ]);
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
     * @param Request $req
     * @param EntityManagerInterface $manager
     * @param $id
     * @return Response
     */
    public function profile(Request $req,EntityManagerInterface $manager,$id)
    {   
        $user=$manager->getRepository(User::class)->find($id);

        return $this->render('account/profil.html.twig',['user'=>$user]);
    
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
    public function loginerror()
    {
        return $this->render('errors/login_error.html.twig');
    }
}
