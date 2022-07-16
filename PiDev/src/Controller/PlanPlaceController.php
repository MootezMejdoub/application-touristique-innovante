<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\PlanPlace;
use App\Controller\PlanController;
use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Form\PlanPlaceType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use App\Form\ReclamationType;
use App\Repository\BlogRepository;
use App\Repository\ReclamationRepository;

use Symfony\Component\Form\FormError;

use ReCaptcha\ReCaptcha; // Include the recaptcha lib
use Dompdf\Dompdf;
use Dompdf\Options;

use Symfony\Component\Validator\Constraints\Json;

use Twilio\Rest\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Serializer\SerializerInterface;

class PlanPlaceController extends AbstractController
{


    // /******* /*RECLAMATION JSON** */


    /**
     * @Route("/displayPlan", name="display_plan")
     */
    public function allplanAction()
    {

        $plan = $this->getDoctrine()->getManager()->getRepository(Plan::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($plan);

        return new JsonResponse($formatted);

    }
    ///******************Ajouter Reclamation*****************************************/
    /**
     * @Route("/addplan", name="add_plan")
     * @Method("POST")
     */

    public function ajouterrPlanAction(Request $request,EntityManagerInterface $entityManager)
    {
        $plan = new Plan();

        $prix = $request->query->get("prix");
        $id=$request->query->get("id");
        $user=$entityManager->getRepository(Utilisateur::class)->find($id);
        $titre = $request->query->get("titre");
        $description= $request->query->get("description");


        $placerest = $request->query->get("nmbrPlacesReste");
        $placemax = $request->query->get("nmbrPlacesMax");

        $note = $request->query->get("note");



        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');

        $plan->setDescription($description);
        $plan->setPrix($prix);
        $plan->setIdguide($user);
        $plan->setTitre($titre);
        $plan->setNmbrplacesmax($placemax);
        $plan->setNmbrplacesreste($placerest);

        $plan->setNote($note);

        $em->persist($plan);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($plan);
        return new JsonResponse($formatted);
    }
    /******************Supprimer Reclamation*****************************************/

    /**
     * @Route("/deletePlan", name="delete_plan")
     * @Method("DELETE")
     */

    public function deleteReclamationAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Plan::class)->find($id);
        if($reclamation!=null ) {
            $em->remove($reclamation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("plan a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id plan invalide.");


    }
    /******************Modifier Reclamation*****************************************/
    /**
     * @Route("/updatePlan", name="updzzzate_plann")
     * @Method("PUT")
     */
    public function modifierReclamationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $plan = $this->getDoctrine()->getManager()
            ->getRepository(Plan::class)
            ->find($request->get("id"));




        $plan->setDescription($request->get("description"));
        $plan->setPrix($request->get("prix"));

        $plan->setTitre($request->get("titre"));
        $plan->setNmbrplacesmax($request->get("nmbrplacesmax"));
        $plan->setNmbrplacesreste($request->get("nmbrplacesreste"));
        $plan->setNote($request->get("note"));



        $em->persist($plan);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($plan);
        return new JsonResponse("plan a ete modifiee avec success.");

    }


    /**
     * @Route("/planplace/", name="app_plan_place_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $planPlaces = $entityManager
            ->getRepository(PlanPlace::class)
            ->findAll();

        return $this->render('plan_place/index.html.twig', [
            'plan_places' => $planPlaces,
        ]);
    }

    /**
     * @Route("/newPLANPLACE", name="app_plan_place_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $flashy->info("PLACE");
        $planPlace = new PlanPlace();
        $form = $this->createForm(PlanPlaceType::class, $planPlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $flashy->info("NEW PLACE");
            $entityManager->persist($planPlace);
            $entityManager->flush();


            return $this->redirectToRoute('app_plan_show', ['id' => $planPlace->getIdplan()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plan_place/new.html.twig', [
            'plan_place' => $planPlace,
            'planPlaceForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/planplace/{ref}", name="app_plan_place_show", methods={"GET"})
     */
    public function show(PlanPlace $planPlace): Response
    {
        return $this->render('plan_place/show.html.twig', [
            'plan_place' => $planPlace,
        ]);
    }

    /**
     * @Route("/planplace/{ref}/edit", name="app_plan_place_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PlanPlace $planPlace, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanPlaceType::class, $planPlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_plan_place_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plan_place/edit.html.twig', [
            'plan_place' => $planPlace,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/planplace/delete/{ref}", name="app_plan_place_delete", methods={"POST"})
     */
    public function delete(Request $request, PlanPlace $planPlace, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planPlace->getRef(), $request->request->get('_token'))) {
            $entityManager->remove($planPlace);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_plan_show', ['id' => $planPlace->getIdplan()->getId()], Response::HTTP_SEE_OTHER);
    }
}
