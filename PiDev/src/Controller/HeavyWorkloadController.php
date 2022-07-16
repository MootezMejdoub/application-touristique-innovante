<?php
// src/Controller/HeavyWorkloadController.php

namespace App\Controller;
use App\Message\GenerateReport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HeavyWorkloadController extends AbstractController
{
    /**
     * @Route("/", name="heavy_work_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request)
    {
        $initialData = ['firstName' => '', 'phoneNumber' => ''];

        $form = $this->createFormBuilder($initialData)
            ->add('firstName', TextType::class)
            ->add('phoneNumber', TextType::class)

            ->add('save', SubmitType::class, ['label' => 'Generate report'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->dispatchMessage(new GenerateReport($data['firstName'], $data['phoneNumber']));

            return $this->redirectToRoute('heavy_work_show', [
                'firstName' => $data['firstName'],
            ]);
        }

        return $this->render('new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/complete", name="heavy_work_show")
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        return $this->render('show.html.twig', [
            'firstName' => $request->query->get('firstName')
        ]);
    }
}
