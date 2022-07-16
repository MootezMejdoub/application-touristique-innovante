<?php

namespace App\Controller;

use App\Entity\Place;
use App\Entity\PlaceImage;
use App\Entity\Plan;
use App\Entity\PlanImage;
use App\Entity\PlanPlace;
use App\Form\PlaceImageType;
use App\Form\PlaceType;
use App\Form\PlanImageType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/place")
 */
class PlaceController extends AbstractController
{
    /**
     * @Route("/", name="app_place_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $user=$this->getUser();
        $places = $entityManager
            ->getRepository(Place::class)
            ->findAll();
        $placeImages = $entityManager
            ->getRepository(PlaceImage::class)
            ->findAll();
        if(in_array('ROLE_ADMIN',$user->getRoles(),true))
        {
            return $this->render('place/back_office/index.html.twig', [
                'places' => $places,
            ]);
        }
        elseif(in_array('ROLE_CLIENT',$user->getRoles(),true))
        {
            return $this->render('place/front_office/client/index.html.twig', [
                'places' => $places,
                'placeImages'=>$placeImages
            ]);
        }
        elseif (in_array('ROLE_GUIDE',$user->getRoles(),true))
        {
            return $this->render('place/front_office/guide/index.html.twig', [
                'places' => $places,
                'placeImages'=>$placeImages
            ]);
        }
        else
        {
            return $this->redirectToRoute('app_utilisateur_new', [], Response::HTTP_SEE_OTHER);
        }

    }

    /**
     * @Route("/new", name="app_place_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        $user=$this->getUser();
        $place = new Place();
        $place->setNote(0);
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($place);
            $entityManager->flush();
            $flashy->success('Place: '.$place->getNom(). "ajouter avec succes");
            return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
        }
        if(in_array('ROLE_ADMIN',$user->getRoles(),true))
        {
            return $this->render('place/back_office/new.html.twig', [
                'place' => $place,
                'form' => $form->createView(),
            ]);
        }
        elseif(in_array('ROLE_CLIENT',$user->getRoles(),true))
        {
            return $this->render('place/front_office/client/new.html.twig', [
                'place' => $place,
                'form' => $form->createView(),
            ]);
        }
        elseif(in_array('ROLE_GUIDE',$user->getRoles(),true))
        {
            return $this->render('place/front_office/guide/new.html.twig', [
                'place' => $place,
                'form' => $form->createView(),
            ]);
        }
        else
        {
            return $this->redirectToRoute('app_utilisateur_new', [], Response::HTTP_SEE_OTHER);
        }

    }

    /**
     * @Route("/{id}", name="app_place_show", methods={"GET"})
     */
    public function show(Request $request, EntityManagerInterface $entityManager ,Place $place,FlashyNotifier $flashy): Response
    {
        $user=$this->getUser();
        $placeImage = new PlaceImage();
        $placeImage->setIdplace($place);
        $placeImageForm = $this->createForm(PlaceImageType::class, $placeImage);
        $placeImages = $entityManager
            ->getRepository(PlaceImage::class)
            ->findBy(array('idplace'=>$place));

        if(in_array('ROLE_ADMIN',$user->getRoles(),true))
        {
            return $this->render('place/back_office/show.html.twig', [
                'place'          => $place,
                'placeImages'    => $placeImages,
                'placeImageForm' => $placeImageForm->createView()
            ]);
        }
        elseif(in_array('ROLE_CLIENT',$user->getRoles(),true))
        {
            return $this->render('place/front_office/client/show.html.twig', [
                'place'          => $place,
                'placeImages'    => $placeImages,
                'placeImageForm' => $placeImageForm->createView()
            ]);
        }
        elseif(in_array('ROLE_GUIDE',$user->getRoles(),true))
        {
            return $this->render('place/front_office/guide/show.html.twig', [
                'place'          => $place,
                'placeImages'    => $placeImages,
                'placeImageForm' => $placeImageForm->createView()
            ]);
        }
        else
        {
            return $this->redirectToRoute('app_utilisateur_new', [], Response::HTTP_SEE_OTHER);
        }

    }

    /**
     * @Route("/{id}/edit", name="app_place_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Place $place, EntityManagerInterface $entityManager): Response
    {
        $user=$this->getUser();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
        }
        if(in_array('ROLE_ADMIN',$user->getRoles(),true))
        {
            return $this->render('place/back_office/edit.html.twig', [
                'place' => $place,
                'form' => $form->createView(),
            ]);
        }
        elseif(in_array('ROLE_CLIENT',$user->getRoles(),true))
        {
            return $this->render('place/front_office/client/edit.html.twig', [
                'place' => $place,
                'form' => $form->createView(),
            ]);
        }
        elseif(in_array('ROLE_GUIDE',$user->getRoles(),true))
        {
            return $this->render('place/front_office/guide/edit.html.twig', [
                'place' => $place,
                'form' => $form->createView(),
            ]);
        }
        else
        {
            return $this->redirectToRoute('app_utilisateur_new', [], Response::HTTP_SEE_OTHER);
        }

    }

    /**
     * @Route("/{id}", name="app_place_delete", methods={"POST"})
     */
    public function delete(Request $request, Place $place, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$place->getId(), $request->request->get('_token'))) {
            $entityManager->remove($place);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
    }
}
