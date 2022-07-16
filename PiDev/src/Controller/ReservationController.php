<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeImmutableToDateTimeTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{


    /*----------------------- display reservation Mobile */
    /**
     * @Route("/Reservationmobile", name="appshowReservationmobile")
     */
    public function mobilefindReservation(ReservationRepository $reservationRepository, Request $request, NormalizerInterface $Normalizer): Response
    {
        $numreservation=$request->get('numreservation');

        $repository = $this->getdoctrine()->getRepository(Reservation::class);
        $reservation = $repository->findAll();
        $jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }




    /**
     * @Route("/index", name="reservation_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {

        $reservation = new Reservation();
        //$reservation->getplan();
        $form = $this->createForm(ReservationType::class, $reservation);
        /*$reservations = $entityManager->createQueryBuilder('r')
            ->select('p.titre')
        ->from('Reservation','r')
        ->join('r.plan','p')
        ->select('p.titre')
        ->getQuery()
        ->getResult();*/
        $reservations = $entityManager->getRepository(Reservation::class)->findAll();


        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
            'form' => $form->createView()
        ]);
    }








    /**
     * @Route("/new", name="reservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $user=new Utilisateur();

        $user=$entityManager->getRepository(Utilisateur::class)->find(43);

        try {
            $idClient = $this->container->get('security.context')->getToken()->getUser()->getId();
        }
        catch (ContainerExceptionInterface $e) {
            //echo "$e";
        }

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /* $entityManager->createQuery('Update App\Entity\Reservation r join App\Entity\Plan p with r.plan.id=p.id join
              App\Entity\Utilisateur u with r.idClient=$idClient set r.nbrplace:place,r.getDatedebut():dateDeb,r.getDatefin():fin,p.titre:titre  ')

              ->getResult();
  */
            //  dd($request);



            $res=$form->getData();
            $reservation->setIdclient($user);//$this->getUser();
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success','reservation ajoutée avec succée ');

            return $this->redirectToRoute('showResByUser', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/index.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/show", name="reservation_show", methods={"GET"})
     *
     */
    public function show(/*Reservation $reservation*/): Response
    {
        $reservations = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findAll();

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservations,
        ]);
    }

    /**
     * @Route("/edit/{numreservation}/", name="reservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            return $this->redirectToRoute('showResByUser', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);

    }










    /**
     * @Route("/delete/{numreservation}", name="reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getNumreservation(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('showResByUser', [], Response::HTTP_SEE_OTHER);
    }




    /**
     *
     *  @Route("/showResByUser", name="showResByUser", methods={"GET"})
     *
     */

    public function showByUser(Request $request,EntityManagerInterface $em): Response
    {

        $reservation = new Reservation();
        $user=new Utilisateur();
        // $user=$em->getRepository(Utilisateur::class)->find(43);
        $reservations = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findByidclient('43');



        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('showResByUser', [], Response::HTTP_SEE_OTHER);
        }



        return $this->render('reservation/displayResByU.html.twig', [
            'reservation' => $reservations,
            'form' => $form->createView(),
        ]);

    }


    /**
     *
     * @Route("/showResMobile", name="showReservationMobile")
     */


    public function showReservation(Request $request,EntityManagerInterface $em,NormalizerInterface $Normalizer ):Response
    {
        $reservation = new Reservation();
        $user=new Utilisateur();

        $reservation = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findByidclient($request->query->get("id"));


        $jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));

    }












    /**
     * @Route("/addReservationJSON/new",name="addReservationJSON")
     *
     */
    public function addReservationJSON(Request $request, NormalizerInterface $Normalizer,EntityManagerInterface $entityManager)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = new Reservation();
        $user=new Utilisateur();

        $id=$request->query->get("id");

        $user=$entityManager->getRepository(Utilisateur::class)->find($id);

        $reservation->setIdclient($user);

        $reservation->setNbrplace($request->get('nbrplace'));

        $dateDebut=$request->query->get("datedebut");
        $reservation->setDatedebut(new \DateTime($dateDebut));

        $dateFin=$request->query->get("datefin");
        $reservation->setDatefin(new \DateTime($dateFin));

        // $pl=$request->query->get("plan");
        $plan =$em->getRepository(Plan::class)->find(39);

        $reservation->setPlan($plan);


        //$reservation->setCaptchaCode($request->get('captchaCode'));
        /* $reservation->setContenu($request->get('contenu'));
         $reservation->setLabel($request->get('label'));
         $reservation->setResp($request->get('resp'));
         $reservation->setIdPost($request->get('id_post'));*/

        /* $user=new Utilisateur();
         $usr=$em->getRepository(Utilisateur::class)->find('id');
         $reservation->setIdclient($idclient);


         $pl=new Plan();
         $pl=$em->getRepository(Plan::class)->find($plan->setTitre($request->get('titre')));
         $reservation->setPlan($pl);*/

        $em->persist($reservation);
        $em->flush();
        $jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => 'reservation']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/deleteReservationJSON",name="deleteReservationJSON")
     */
    public function deleteReservationJSON(Request $request,NormalizerInterface $Normalizer):Response
    {
        $numreservation=$request->get('numreservation');

        $em=$this->getDoctrine()->getManager();
        $reservation=$em->getRepository(Reservation::class)->find($numreservation);
        $em->remove($reservation);
        $em->flush();
        $jsonContent=$Normalizer->normalize($reservation,'json',['groups'=>'post:read']);

        return new Response("Reservation deleted successfully".json_encode($jsonContent));
    }







    /**
     * @Route("/updateReservationJSON/{numreservation}",name="updateReservationJSON")
     *
     *
     */
    public function updateReservationJSON(Request $request, NormalizerInterface $Normalizer, $numreservation)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository(Reservation::class)->find($numreservation);
        //$reservation=new Reservation();

        $reservation->setNbrplace($request->get('nbrplace'));
        $dateDebut=$request->query->get("datedebut");
        $reservation->setDatedebut(new \DateTime($dateDebut));
        $dateFin=$request->query->get("datefin");
        $reservation->setDatefin(new \DateTime($dateFin));


        $plan =$em->getRepository(Plan::class)->find(3);
        $reservation->setPlan($plan);


        $em->flush();
        $jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => 'post:read']);
        return new Response("update successfully".json_encode($jsonContent));;

    }













}
