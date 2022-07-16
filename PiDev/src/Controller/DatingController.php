<?php

namespace App\Controller;

use App\Entity\Dating;
use App\Entity\Interest;
use App\Entity\Matching;
use App\Form\DatingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dating")
 */
class DatingController extends AbstractController
{
    /**
     * @Route("/", name="app_dating_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $datings = $entityManager
            ->getRepository(Dating::class)
            ->findAll();

        return $this->render('dating/index.html.twig', [
            'datings' => $datings,
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_dating_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,$id): Response
    {
             $dating = new Dating();
             $matching = $entityManager
            ->getRepository(Matching::class)
            ->find($id);

        $intersetClient1 = $entityManager
            ->getRepository(Interest::class)
            ->find($matching->getClient1()->getIdInterest());

        $intersetClient2 = $entityManager
            ->getRepository(Interest::class)
            ->find($matching->getClient1()->getIdInterest());

             $dating->setIdMatch($matching);
             $dating->setDiscount(($intersetClient1->getScore()+$intersetClient2->getScore())/10  );


            $entityManager->persist($dating);
            $entityManager->flush();

            return $this->redirectToRoute('mylisT', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @Route("/{idDate}", name="app_dating_show", methods={"GET"})
     */
    public function show(Dating $dating): Response
    {
        return $this->render('dating/show.html.twig', [
            'dating' => $dating,
        ]);
    }

    /**
     * @Route("/{idDate}/edit", name="app_dating_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Dating $dating, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DatingType::class, $dating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dating_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dating/edit.html.twig', [
            'dating' => $dating,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idDate}", name="app_dating_delete", methods={"POST"})
     */
    public function delete(Request $request, Dating $dating, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dating->getIdDate(), $request->request->get('_token'))) {
            $entityManager->remove($dating);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dating_index', [], Response::HTTP_SEE_OTHER);
    }
}
