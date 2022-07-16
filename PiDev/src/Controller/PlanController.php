<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Plan;
use App\Entity\PlanImage;
use App\Entity\PlanPlace;
use App\Entity\Utilisateur;
use App\Form\Commentaire1Type;
use App\Form\PlanImageType;
use App\Form\PlanPlaceType;
use App\Form\PlanType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/plan")
 */
class PlanController extends AbstractController
{
    /**
     * @Route("/", name="app_plan_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $session = $request->getSession();
        $uss=$this->getUser();

        $user=$this->getUser();
        //$user=$entityManager->getRepository(Utilisateur::class)->find($session->get('id'));

        $planImages = $entityManager
            ->getRepository(PlanImage::class)
            ->findAll();
        $plans = $entityManager
            ->getRepository(Plan::class)
            ->findAll();

        if(in_array('ROLE_ADMIN',$user->getRoles(),true))
        {
            return $this->render('plan/back_office/index.html.twig', [
                'plans' => $plans,
                ]);
        }
        elseif(in_array('ROLE_CLIENT',$user->getRoles(),true))
        {

            return $this->render('plan/front_office/client/index.html.twig', [
                'plans' => $plans,
                'planImages' => $planImages]);
        }
        elseif(in_array('ROLE_GUIDE',$user->getRoles(),true))
        {
            $plans = $entityManager
                ->getRepository(Plan::class)
                ->findBy(array('idguide'=>$uss->getSalt()));

            return $this->render('plan/front_office/guide/index.html.twig', [
                'plans' => $plans,
                'planImages' => $planImages]);
        }
        else
        {
            return $this->redirectToRoute('app_utilisateur_new', [], Response::HTTP_SEE_OTHER);
        }


    }

    /**
     * @Route("/new", name="app_plan_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {$session = $request->getSession();

        $user=$this->getUser();
        //$user=$entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        $role=$this->getUser()->getRoles();
        $plan = new Plan();
        $plan->setNote(0);
        $plan->setIdguide($user);
        if(in_array('ROLE_GUIDE',$user->getRoles(),true)){
            $plan->setIdguide($user);
        }
            $form = $this->createForm(PlanType::class, $plan);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plan->setNmbrplacesreste($plan->getNmbrplacesmax());

            $entityManager->persist($plan);
            $entityManager->flush();

            $flashy->success('Plan: '.$plan->getTitre(). "ajouter avec succes");
            return $this->redirectToRoute('app_plan_index', [], Response::HTTP_SEE_OTHER);
        }
        if(in_array('ROLE_ADMIN',$user->getRoles(),true))
        {
            return $this->render('plan/back_office/new.html.twig', [
                'plan' => $plan,
                'form' => $form->createView(),
            ]);
        }
        elseif(in_array('ROLE_CLIENT',$user->getRoles(),true))
        {
            return $this->render('plan/front_office/client/new.html.twig', [
                'plan' => $plan,
                'form' => $form->createView(),
            ]);
        }
        elseif(in_array('ROLE_GUIDE',$user->getRoles(),true))
        {

            return $this->render('plan/front_office/guide/new.html.twig', [
                'plan' => $plan,
                'form' => $form->createView(),
            ]);
        }
        else
        {
            return $this->redirectToRoute('app_utilisateur_new', [], Response::HTTP_SEE_OTHER);
        }

    }

    /**
     * @Route("/{id}", name="app_plan_show", methods={"GET", "POST"}))
     */
    public function show(Request $request, EntityManagerInterface $entityManager ,Plan $plan,FlashyNotifier $flashy): Response
    {
        $commentaires = $entityManager
            ->getRepository(Commentaire::class)
            ->findAll();


        $commentaire = new Commentaire();
        $form2 = $this->createForm(Commentaire1Type::class, $commentaire);
        $form2->handleRequest($request);
        /////////////////////houni aabit el cle etrangere bel user eli amalt bih el login/////////////////////
        $user = $this->getUser();
        $commentaire->setIdclient($user);
/////////////////////////////////////////////
        $commentaire->setDatePost(new \DateTime('now'));

        if ($form2->isSubmitted() && $form2->isValid()) {
            /// on recupere le contenu du champ parentid
            $parentid=$form2->get('parentid')->getData();

            //on va chercher le comm corresp
            $parent=$entityManager->getRepository(Commentaire::class)->find($parentid);
            //on definit le parent
            $commentaire->setParent($parent);
            ///////////////
$commentaire->setPlan($plan);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_new', [], Response::HTTP_SEE_OTHER);

        }
        $session = $request->getSession();
        $user=$this->getUser();
        //$user=$entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        $planPlace = new PlanPlace();
        $planPlace->setIdplan($plan);
        $planPlaceForm = $this->createForm(PlanPlaceType::class, $planPlace);
        $planPlaces = $entityManager
            ->getRepository(PlanPlace::class)
            ->findBy(array('idplan'=>$plan));

        $planImage = new PlanImage();
        $planImage->setIdplan($plan);
        $planImageForm = $this->createForm(PlanImageType::class, $planImage);
        $planImages = $entityManager
            ->getRepository(PlanImage::class)
            ->findBy(array('idplan'=>$plan));

        if(in_array('ROLE_ADMIN',$user->getRoles(),true))
        {
            return $this->render('plan/back_office/show.html.twig', [
                'plan'          => $plan,
                'planPlaces'    => $planPlaces,
                'planPlaceForm' => $planPlaceForm->createView(),
                'planImages'    => $planImages,
                'planImageForm' => $planImageForm->createView()
            ]);
        }
        elseif(in_array('ROLE_CLIENT',$user->getRoles(),true))
        {
            return $this->render('plan/front_office/client/show.html.twig', [
                'plan'          => $plan,
                'commentaire' => $commentaire,
                'commentaires' => $commentaires,
                'form2' => $form2->createView(),
                'planPlaces'    => $planPlaces,
                'planPlaceForm' => $planPlaceForm->createView(),
                'planImages'    => $planImages,
                'planImageForm' => $planImageForm->createView()
            ]);
        }
        elseif(in_array('ROLE_GUIDE',$user->getRoles(),true))
        {
            return $this->render('plan/front_office/guide/show.html.twig', [
                'plan'          => $plan,
                'planPlaces'    => $planPlaces,
                'planPlaceForm' => $planPlaceForm->createView(),
                'planImages'    => $planImages,
                'planImageForm' => $planImageForm->createView()
            ]);
        }
        else
        {
            return $this->redirectToRoute('app_utilisateur_new', [], Response::HTTP_SEE_OTHER);
        }

    }

    /**
     * @Route("/{id}/edit", name="app_plan_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Plan $plan, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        $session = $request->getSession();
        $user=$this->getUser();
        //$user=$entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        $form = $this->createForm(PlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $flashy->success('Modifier Plan: '.$plan->getTitre());
            $entityManager->flush();

            return $this->redirectToRoute('app_plan_index', [], Response::HTTP_SEE_OTHER);
        }
        if(in_array('ROLE_ADMIN',$user->getRoles(),true))
        {
            return $this->render('plan/back_office/edit.html.twig', [
                'plan' => $plan,
                'form' => $form->createView(),
            ]);
        }
        elseif(in_array('ROLE_CLIENT',$user->getRoles(),true))
        {

            return $this->render('plan/front_office/client/edit.html.twig', [
                'plan' => $plan,
                'form' => $form->createView(),

            ]);
        }
        elseif(in_array('ROLE_GUIDE',$user->getRoles(),true))
        {
            $planImages = $entityManager
                ->getRepository(PlanImage::class)
                ->findBy(array('idplan'=>$plan));
            return $this->render('plan/front_office/guide/edit.html.twig', [
                'plan' => $plan,
                'form' => $form->createView(),
                'planImages' => $planImages
            ]);
        }
        else
        {
            return $this->redirectToRoute('app_utilisateur_new', [], Response::HTTP_SEE_OTHER);
        }

    }

    /**
     * @Route("/delete/{id}", name="app_plan_delete", methods={"POST"})
     */
    public function delete(Request $request, Plan $plan, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plan->getId(), $request->request->get('_token'))) {
            $flashy->error(' Plan: '.$plan->getTitre().' est supprimer');
            $entityManager->remove($plan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_plan_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/ajoutAdminPlan", name="app_plan_ajoutAdmin", methods={"POST"})
     */
    public function ajoutAdmin(Request $request, EntityManagerInterface $entityManager ,Plan $plan,FlashyNotifier $flashy){
        $session = $request->getSession();
        $uss=$this->getUser();


        $user=$entityManager->getRepository(Utilisateur::class)->find($uss->getSalt());
        $role=$this->getUser()->getRoles();
        $plan = new Plan();
        $plan->setNote(0);
        $plan->setIdguide($user);
        $form = $this->createForm(PlanType::class, $plan);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plan->setNmbrplacesreste($plan->getNmbrplacesmax());
            $user=$entityManager->getRepository(Utilisateur::class)->find($uss->getSalt());

            $entityManager->persist($plan);
            $entityManager->flush();

            $flashy->success('Plan: '.$plan->getTitre(). "ajouter avec succes");
            return $this->redirectToRoute('app_plan_index', [], Response::HTTP_SEE_OTHER);
    }
        return $this->render('plan/back_office/new.html.twig', [
            'plans' => $plan,
        ]);  }
}
