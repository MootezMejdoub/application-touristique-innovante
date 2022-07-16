<?php

namespace App\Controller;

use App\Entity\Interest;
use App\Entity\Utilisateur;
use App\Form\InterestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/interestinterest")
 */
class InterestController extends AbstractController
{
    /**
     * @Route("/", name="app_interest_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $interests = $entityManager
            ->getRepository(Interest::class)
            ->findAll();

        return $this->render('interest/index.html.twig', [
            'interests' => $interests,
        ]);
    }


    /**
     * @Route("/front", name="app_interest_front", methods={"GET"})
     */
    public function front(EntityManagerInterface $entityManager): Response
    {
        $interests = $entityManager
            ->getRepository(Interest::class)
            ->findAll();

        return $this->render('front.html.twig', [
            'interests' => $interests,
        ]);
    }


    /**
     * @Route("/back", name="app_interest_back", methods={"GET"})
     */
    public function back(EntityManagerInterface $entityManager): Response
    {
        $interests = $entityManager
            ->getRepository(Interest::class)
            ->findAll();

        return $this->render('back.html.twig', [
            'interests' => $interests,
        ]);
    }

    /**
     * @Route("/new", name="app_interest_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $uss=$this->getUser();

        $interest = new Interest();
        $idt =0;
        $score = 0;
        $form = $this->createForm(InterestType::class, $interest);
        $form->handleRequest($request);
        $user = $entityManager
            ->getRepository(Utilisateur::class)
            ->find($uss->getSalt());
        $interests = $entityManager
            ->getRepository(Interest::class)
            ->findAll();
        foreach ($interests as $value) {
            $idt = $value->getIdIntrest();
        }

        if ($form->isSubmitted() && $form->isValid()) {

            if($interest->getFood())
                $score=$score+50;
            if($interest->getHealth())
                $score=$score+100;
            if($interest->getHistory())
                $score=$score+70;
            if($interest->getSport())
                $score=$score+200;


            $user->setIdInterest($idt+1);
            $interest->setScore($score);
            $entityManager->persist($user);
            $entityManager->persist($interest);
            $entityManager->flush();

            return $this->redirectToRoute('app_interest_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interest/new.html.twig', [
            'interest' => $interest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/showScore", name="app_interest_show")
     */
    public function show(EntityManagerInterface $entityManager,Request $request): Response
    {

        $uss=$this->getUser();

        $user = $entityManager
            ->getRepository(Utilisateur::class)
            ->find($uss->getSalt());
        $interest = $entityManager
            ->getRepository(Interest::class)
            ->find($user->getIdInterest());

        return $this->render('interest/monScore.html.twig', [
            'interest' => $interest,
        ]);
    }

    /**
         * @Route("/{idIntrest}/edit", name="app_interest_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Interest $interest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InterestType::class, $interest);
        $form->handleRequest($request);

        $score = 0;




        if ($form->isSubmitted() && $form->isValid()) {
            if($interest->getFood())
                $score=$score+50;
            if($interest->getHealth())
                $score=$score+100;
            if($interest->getHistory())
                $score=$score+70;
            if($interest->getSport())
                $score=$score+200;
            $interest->setScore($score);
            $entityManager->flush();

            return $this->redirectToRoute('app_interest_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interest/edit.html.twig', [
            'interest' => $interest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idIntrest}", name="app_interest_delete", methods={"POST"})
     */
    public function delete(Request $request, Interest $interest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$interest->getIdIntrest(), $request->request->get('_token'))) {
            $entityManager->remove($interest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_interest_index', [], Response::HTTP_SEE_OTHER);
    }
}
