<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/facture")
 */
class FactureController extends AbstractController
{
    /**
     * @Route("/index/{numreservation}", name="app_facture_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,Request $request,$numreservation ): Response
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
         $pdfOptions->set('isRemoteEnabled',true);
        // Instanti ate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->set_base_path("css");
        $dompdf->set_option('isRemoteEnabled',TRUE);
        $user=new Utilisateur();
        $res=new Reservation();


      //  $user=$entityManager->getRepository(Utilisateur::class)->find('43');
        /*$user=$entityManager->getRepository(Utilisateur::class)->findAll();

        //$res=$entityManager->getRepository(Reservation::class)->find('16');
        $res=$entityManager->getRepository(Reservation::class)->findAll();

        $factures= $entityManager->getRepository(Facture::class)->findAll();*/



       /* $factures=$this->getDoctrine()->getRepository(Facture::class)
            ->findBynumReservation('16');*/
        $factures=$this->getDoctrine()->getRepository(Facture::class)->findBynumReservation($numreservation);



        // Retrieve the HTML generated in our twig file
        $html =$this->renderView('facture/index.html.twig', [
            'factures' => $factures,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();


        // Output the generated PDF to Browser (force download)
        $dompdf->stream("facture.pdf", [
            "Attachment" => true
        ]);



    /*
        $user=new Utilisateur();
        $use=$entityManager->getRepository(Utilisateur::class)->find(43);
        $res=$entityManager->getRepository(Reservation::class)->findByidclient('43');
        $factures = $entityManager
            ->getRepository(Facture::class)
            ->findAll();
             $factures=$this->getDoctrine()->getRepository(Facture::class)
            ->findByidclient('43');*/

        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
        ]);

    }

    /**
     * @Route("/new", name="app_facture_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/", name="app_facture_show", methods={"GET"})
     */
    public function show(/*Facture $facture*/): Response
    {
        $factures = $this->getDoctrine()
            ->getRepository(Facture::class)
            ->findAll();
        return $this->render('facture/show.html.twig', [
            'facture' => $factures,
        ]);
    }

    /**
     * @Route("/edit/{idfacture}", name="app_facture_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{idfacture}", name="app_facture_delete", methods={"POST"})
     */
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getIdfacture(), $request->request->get('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facture_show', [], Response::HTTP_SEE_OTHER);
    }

    /**
     *
     *
     *@Route("/download",name="download_ind",methods={"GET"} )
     */
    public function download(EntityManagerInterface $entityManager,FactureRepository $factureRepository):Response
    {

        //  Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $user=new Utilisateur();
        $rer=new Reservation();
        $use=$entityManager->getRepository(Utilisateur::class)->find(43);
       $res=$entityManager->getRepository(Reservation::class)->findByidclient(43);
        $factures = $entityManager
            ->getRepository(Facture::class)
            ->findAll();
        $facture=$this->getDoctrine()->getRepository(Facture::class)->findByidclient('43');


           // Retrieve the HTML generated in our twig file
           $html = $this->renderView('facture/index.html.twig', [
               'factures' => $factures,
           ]);

           // Load HTML to Dompdf
           $dompdf->loadHtml($html);

           // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
           $dompdf->setPaper('A4', 'portrait');

           // Render the HTML as PDF
           $dompdf->render();

           // Output the generated PDF to Browser (force download)
           $dompdf->stream("my.pdf", [
               "Attachment" => true
           ]);

        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
        ]);


    }





































}
