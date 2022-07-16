<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Utilisateur;
use App\Form\ChatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chat")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/recrec", name="app_chaat_index")
     */
    public function index(EntityManagerInterface $entityManager)
    {

    }}