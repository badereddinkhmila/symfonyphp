<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RandezvousController extends AbstractController
{
    /**
     * @Route("/randezvous", name="randezvous")
     */
    public function index()
    {
        return $this->render('randezvous/randezvous.html.twig');
    }
}
