<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\CommentDislike;
use App\Entity\CommentLike;
use App\Form\Commentaire1Type;
use App\Form\CommentaireType;
use App\Repository\CommentDislikeRepository;

use App\Repository\CommentLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commentaire")
 */
class CommentaireController extends AbstractController
{
    /**
     * @Route("/", name="app_commentaire_index", methods={"GET"})
     */
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $commentaires = $entityManager
            ->getRepository(Commentaire::class)
            ->findAll();







        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaires,
        ]);
    }

    /**
     * @Route("/new", name="app_commentaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaires = $entityManager
            ->getRepository(Commentaire::class)
            ->findAll();


        $commentaire = new Commentaire();
        $form = $this->createForm(Commentaire1Type::class, $commentaire);
        $form->handleRequest($request);
        /////////////////////houni aabit el cle etrangere bel user eli amalt bih el login/////////////////////
          $user = $this->getUser();
$commentaire->setIdclient($user);
/////////////////////////////////////////////
 $commentaire->setDatePost(new \DateTime('now'));

        if ($form->isSubmitted() && $form->isValid()) {
            /// on recupere le contenu du champ parentid
            $parentid=$form->get('parentid')->getData();

            //on va chercher le comm corresp
            $parent=$entityManager->getRepository(Commentaire::class)->find($parentid);
           //on definit le parent
            $commentaire->setParent($parent);
            ///////////////

            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_new', [], Response::HTTP_SEE_OTHER);

        }
$this->addFlash('message','Votre commentaire a été bien posté');
        return $this->render('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'commentaires' => $commentaires,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{idcomm}", name="app_commentaire_show", methods={"GET"})
     */
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/{idcomm}/edit", name="app_commentaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idcomm}", name="app_commentaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getIdcomm(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_new', [], Response::HTTP_SEE_OTHER);
    }

    /**
     *
     * @Route ("/{comm}/like",name="commentaire_like")
     * @param Commentaire $comm
     * @param ObjectManager $manager
     * @param CommentLikeRepository $commentLikeRepository
     * @return Response
     */

    public function like(Commentaire $comm,EntityManagerInterface $manager,CommentLikeRepository $commentLikeRepository):Response
    {
        $user=$this->getUser();
        if(!$user) return $this->json([
            'code'=>403,
            'message'=>"unauthorized"
        ],403);
////dans le cas ou un commentaire est liké par un user
if($comm->isLikedByUser($user))
{
    ////alors on enleve le like suppression
    $like=$commentLikeRepository->findOneBy([
        'comm'=>$comm,
        'user'=>$user
    ]);
    $manager->remove($like);
    $manager->flush();

    return $this->json([
        'code'=>200,
        'message'=> 'like supprimé',
        'likes'=> $commentLikeRepository->count(['comm'=>$comm])
    ],200);




}
////nouveau like//////
$like=new CommentLike();
$like->setComm($comm)
    ->setUser($user);
$manager->persist($like);
$manager->flush();

return $this->json(['code'=>200,
    'message'=>'like bien ajoute',
    'likes'=>$commentLikeRepository->count(['comm'=>$comm])
],200);

    }





    /**
     *
     * @Route ("/{comm}/dislike",name="commentaire_dislike")
     * @param Commentaire $comm
     * @param ObjectManager $manager
     * @param CommentDislikeRepository $commentDislikeRepository
     * @return Response
     */

    public function dislike(Commentaire $comm,EntityManagerInterface $manager,CommentDislikeRepository $commentDislikeRepository):Response
    {
        $user=$this->getUser();
        if(!$user) return $this->json([
            'code'=>403,
            'message'=>"unauthorized"
        ],403);
////dans le cas ou un commentaire est liké par un user
        if($comm->isDislikedByUser($user))
        {
            ////alors on enleve le like suppression
            $dislike=$commentDislikeRepository->findOneBy([
                'comm'=>$comm,
                'user'=>$user
            ]);
            $manager->remove($dislike);
            $manager->flush();

            return $this->json([
                'code'=>200,
                'message'=> 'like supprimé',
                'dislikes'=> $commentDislikeRepository->count(['comm'=>$comm])
            ],200);




        }
////nouveau like//////
        $dislike=new CommentDislike();
        $dislike->setComm($comm)
            ->setUser($user);
        $manager->persist($dislike);
        $manager->flush();

        return $this->json(['code'=>200,
            'message'=>'dislike bien ajoute',
            'dislikes'=>$commentDislikeRepository->count(['comm'=>$comm])
        ],200);

    }



}
