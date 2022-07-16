<?php

namespace App\Controller;

use App\Entity\Interest;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class InterestApiController extends AbstractController
{

    /**
     * @Route("/api/addInterst/{id}", name="api_addInterst")
     */
    public function newInterst(Request  $request, EntityManagerInterface $entityManager,$id )
    {

        $em = $this->getDoctrine()->getManager();
        $interest = new Interest();
        $idt =0;
        $score = 0;

        $user = $entityManager
            ->getRepository(Utilisateur::class)
            ->find($id);
        $interest->setHistory($request->get('history'));
        $interest->setFood($request->get('food'));
        $interest->setHealth($request->get('health'));
        $interest->setSport($request->get('sport'));

        $interests = $entityManager
            ->getRepository(Interest::class)
            ->findAll();
        foreach ($interests as $value) {
            $idt = $value->getIdIntrest();
        }

        if($interest->getFood())
            $score=$score+50;
        if($interest->getHealth())
            $score=$score+100;
        if($interest->getHistory())
            $score=$score+70;
        if($interest->getSport())
            $score=$score+200;

        $interest->setScore($score);
        $user->setIdInterest($idt+1);
        $entityManager->persist($user);
        $entityManager->persist($interest);
        $entityManager->flush();
        $em->persist($interest);
        $em->flush();

        $serializer = new Serializer( [new ObjectNormalizer()]);
        $formated = $serializer->normalize($interest);
        return new JsonResponse($formated);
    }





    /**
     * @Route("/api/updateInterst", name="update_Interst")
     */
    public function UpdateInterst(Request $request , EntityManagerInterface $entityManager )
    {
        $em=$this->getDoctrine()->getManager();

        $score = 0;

        $interest = $entityManager
            ->getRepository(Interest::class)
            ->find($request->get('id'));

        $interest->setFood($request->get('food'));
        $interest->setHealth($request->get('health'));
        $interest->setSport($request->get('sport'));
        $interest->setHistory($request->get('history'));


        if($interest->getFood())
            $score=$score+50;
        if($interest->getHealth())
            $score=$score+100;
        if($interest->getHistory())
            $score=$score+70;
        if($interest->getSport())
            $score=$score+200;

        $interest->setScore($score);


        $em->persist($interest);
        $em->flush();

        $serializer = new Serializer( [new ObjectNormalizer()]);
        $formated = $serializer->normalize("ok");
        return new JsonResponse($formated);

    }


    /**
     * @Route("/scoreShow/{id}", name="scoreShow")
     */
    public function show(EntityManagerInterface $entityManager,$id,NormalizerInterface $normalizer): Response
    {
        $user = $entityManager
            ->getRepository(Utilisateur::class)
            ->find($id);
        $interest = $entityManager
            ->getRepository(Interest::class)
            ->findBy(array('idIntrest'=>$user->getIdInterest()));

        $jsonContent = $normalizer->normalize($interest,'json',['groups'=>'posts:interest']);
        return new JsonResponse($jsonContent);

    }



}
