<?php

namespace App\Controller;

use App\Entity\PlaceImage;
use App\Entity\PlanImage;
use App\Form\PlaceImageType;
use App\Form\PlanImageType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceImageController extends AbstractController
{
    /**
     * @Route("/placeimage/", name="app_place_image_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $placeImages = $entityManager
            ->getRepository(PlaceImage::class)
            ->findAll();

        return $this->render('place_image/index.html.twig', [
            'place_images' => $placeImages,
        ]);
    }

    /**
     * @Route("/newPLACEIMAGE", name="app_place_image_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        $placeImage = new PlaceImage();
        $form = $this->createForm(PlaceImageType::class, $placeImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $file = $placeImage->getPath();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try{
                $file->move(
                    $this->getParameter('places_images_directory'), $fileName
                );
                $placeImage->setPath("t3adet");
            }catch (FileException $e){
                $placeImage->setPath("ERROR");
                if($file == null){
                    $placeImage->setPath("ERROR mta3 null");
                }

            }
            $placeImage->setPath($fileName);
            $entityManager->persist($placeImage);
            $entityManager->flush();
            $flashy->success("Ajout d'Image pour place ".$placeImage->getIdplace()->getNom());
            return $this->redirectToRoute('app_place_show', ['id' => $placeImage->getIdplace()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('place_image/new.html.twig', [
            'place_image' => $placeImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/placeimage/{id}", name="app_place_image_show", methods={"GET"})
     */
    public function show(PlaceImage $placeImage): Response
    {
        return $this->render('place_image/show.html.twig', [
            'place_image' => $placeImage,
        ]);
    }

    /**
     * @Route("/placeimage/{id}/edit", name="app_place_image_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PlaceImage $placeImage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlaceImageType::class, $placeImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_place_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('place_image/edit.html.twig', [
            'place_image' => $placeImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/placeimage/delete/{id}", name="app_place_image_delete", methods={"POST"})
     */
    public function delete(Request $request, PlaceImage $placeImage, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$placeImage->getId(), $request->request->get('_token'))) {
            $flashy->error("L'Image de place ".$placeImage->getIdplace()->getNom()." est supprimer");
            $entityManager->remove($placeImage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_place_show', ['id' => $placeImage->getIdplace()->getId()], Response::HTTP_SEE_OTHER);
    }
}
