<?php

namespace App\Controller;

use App\Entity\PlanImage;
use App\Form\PlanImageType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanImageController extends AbstractController
{
    /**
     * @Route("/planimage/", name="app_plan_image_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $planImages = $entityManager
            ->getRepository(PlanImage::class)
            ->findAll();

        return $this->render('plan_image/index.html.twig', [
            'plan_images' => $planImages,
        ]);
    }

    /**
     * @Route("/newPLANIMAGE", name="app_plan_image_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        $planImage = new PlanImage();
        $form = $this->createForm(PlanImageType::class, $planImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $file = $planImage->getPath();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try{
                $file->move(
                    $this->getParameter('plans_images_directory'), $fileName
                );
                $planImage->setPath("t3adet");
            }catch (FileException $e){
                $planImage->setPath("ERROR");
                if($file == null){
                    $planImage->setPath("ERROR mta3 null");
                }

            }
            $planImage->setPath($fileName);
            $entityManager->persist($planImage);
            $entityManager->flush();
            $flashy->success("Ajout d'Image pour plan ".$planImage->getIdplan()->getTitre());
            return $this->redirectToRoute('app_plan_show', ['id' => $planImage->getIdplan()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plan_image/new.html.twig', [
            'plan_image' => $planImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/planimage/{id}", name="app_plan_image_show", methods={"GET"})
     */
    public function show(PlanImage $planImage): Response
    {
        return $this->render('plan_image/show.html.twig', [
            'plan_image' => $planImage,
        ]);
    }

    /**
     * @Route("/planimage/{id}/edit", name="app_plan_image_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PlanImage $planImage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanImageType::class, $planImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_plan_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plan_image/edit.html.twig', [
            'plan_image' => $planImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/planimage/delete/{id}", name="app_plan_image_delete", methods={"POST"})
     */
    public function delete(Request $request, PlanImage $planImage, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planImage->getId(), $request->request->get('_token'))) {
            $flashy->error("L'Image de plan ".$planImage->getIdplan()->getTitre()." est supprimer");
            $entityManager->remove($planImage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_plan_show', ['id' => $planImage->getIdplan()->getId()], Response::HTTP_SEE_OTHER);
    }
}
