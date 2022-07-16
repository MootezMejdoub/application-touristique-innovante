<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeUsController extends AbstractController
{
    /**
     * @Route("/User", name="app_home_us")
     */
    public function index(): Response
    {
        return $this->render('front.html.twig', [
            'controller_name' => 'HomeUsController',
        ]);
    }
}
