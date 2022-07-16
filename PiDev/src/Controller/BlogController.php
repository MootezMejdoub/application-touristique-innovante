<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Images;
use App\Entity\Rating;
use App\Entity\Utilisateur;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController

{

    /**
     * @Route("/", name="app_blog_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,RatingRepository $ratingRepository,BlogRepository $rep): Response
    {
        $blogs = $entityManager
            ->getRepository(Blog::class)
            ->findAll();

    $rate=$entityManager
        ->getRepository(Rating::class)
        ->findAll();

        $ratingNumber=$ratingRepository->countratingNumber();



        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs,
            'rating'=>$rate,

            'ratingNumber'=>$ratingNumber,
        ]);
    }

    /**
     * @Route("/new", name="app_blog_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();

        $user=new Utilisateur();
        $user=$entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //recuperation des images transmises
            $images = $form->get('images')->getData();
// Boucle sur les images
            foreach ($images as $image) {
// on genere un nouveau nom de fichier unique avecmd5. guessExtension recupere l'extension du fichier
$fichier = md5(uniqid()) . '.' . $image->guessExtension();
//On copie le fichier dans le dossier upload
$image->move($this->getParameter('upload_directory'),
$fichier);
// on stocke l'image dans la bdd (son nom)
                $img = new Images();
                $img->setName($fichier);
                $blog->addImage($img);}
            $blog->setUser($user);
            $blog->setDate(new \DateTime('now'));
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/myBlog", name="app_blog_show")
     */
    public function show(BlogRepository $rep,RatingRepository $ratingRepository,Request $request): Response
    {
        $blog=new Blog();
        $session = $request->getSession();
        $session->get('id');
        $blog=$rep->findByExampleField($session->get('id'));




        return $this->render('blog/show.html.twig', [
            'blog' => $blog,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_blog_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_blog_delete", methods={"POST"})
     */
    public function delete(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_show', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/myBlog/{id}", name="app_blog_showBlog")
     */
    public function showblog($id,EntityManagerInterface $entityManager,RatingRepository $ratingRepository): Response
    {

        $blog = $entityManager
            ->getRepository(Blog::class)
            ->find($id);

        $ratingValue=$ratingRepository->findByBlodId($blog->getId());
        $ratingNumber=$ratingRepository->countratingNumber();


       // $session = $this->get('session');
       // $session->set('ratingValue',  $ratingValue);

        return $this->render('blog/showBlog.html.twig', [
            'blog' => $blog,
            'ratingValue'=>$ratingValue,

        ]);
    }

    /**
     * @Route("/rate/{id}/{rate}", name="app_blog_saveRate")
     */
    public function saveRate($id,EntityManagerInterface $entityManager,$rate){
        $blog = $entityManager
            ->getRepository(Blog::class)
            ->find($id);

        $rating =new Rating();
        $rating->setBlogid($blog);
        $rating->setRateindex($rate);
        $entityManager->persist($rating);
        $entityManager->flush();

        return $this->redirectToRoute('app_blog_showBlog', ['id'=>$id], Response::HTTP_SEE_OTHER);
    }


}
