<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function adminlogin()
    {
        return $this->render('admin/login.html.twig');
    }

    /**
     * @Route("/admin/home",name="admin_home")
     */
    public function adminhome()
    {
        return $this->render('admin/home.html.twig');
    }
}
