<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DoctorController extends AbstractController
{
    /**
     * @Route("/doctor/login", name="doctor_login")
     */
    public function login()
    {
        return $this->render('doctor/login.html.twig');
    }

    /**
     * @Route("/doctor/home",name="doctor_home")
     */
    public function home(){
        return $this->render('doctor/home.html.twig');
    }
}

