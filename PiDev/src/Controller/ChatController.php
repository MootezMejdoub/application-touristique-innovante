<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Utilisateur;
use App\Form\ChatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chat")
 */
class ChatController extends AbstractController
{
    /**
     * @Route("/", name="app_chat_index", methods={"GET", "POST"})
     */
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $user=new Utilisateur();
        $user=$entityManager->getRepository(Utilisateur::class)->find(51);
        $session = $request->getSession();
        $id=$session->get('id');

        $chats = $entityManager
            ->getRepository(Chat::class)
            ->findAll();
        return $this->render('chat/index.html.twig', [
            'chats' => $chats,
            'id'=>$id,

        ]);

    }

    /**
     * @Route("/new", name="app_chat_new", methods={"POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $user = new Utilisateur();
        $session = $request->getSession();
        $user = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        $chat = new Chat();

        // if (isset($_POST['submit'])) {

        $search = $request->request->get('input');
        $chat->setMessage("$search");
        $chat->setUser($user);
        $chat->setDate(new \DateTime('now'));
        $entityManager->persist($chat);
        $entityManager->flush();


    }



    /**
     * @Route("/{id}/edit", name="app_chat_edit", methods={ "POST"})
     */
    public function edit(Request $request, Chat $chat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chat/edit.html.twig', [
            'chat' => $chat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_chat_delete", methods={"POST"})
     */
    public function delete(Request $request, Chat $chat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
    }
}
