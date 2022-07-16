<?php


namespace App\Controller;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use  App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Form\FormSType;
use Symfony\Component\Mime\Email;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\AbstractList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/utilisateur")
 */

class UtilisateurController extends AbstractController
{

    /**
     * @Route("/detailUser2", name="detaiiiiiiil_reclamation22")
     * @Method("GET")
     */

    //Detail Reclamation
    public function affichUser2(Request $request)
    {
        $email = $request->query->get("email");
        $mdp=$request->query->get("mdp");
        $em = $this->getDoctrine()->getManager();
        $utilisateur= $this->getDoctrine()->getManager()->getRepository(Utilisateur::class)->findOneBy(['email'=>$email]);


        if ($utilisateur)
        {
            if($utilisateur->getMdp())
            {
                $encoder = new JsonEncoder();
                $normalizer = new ObjectNormalizer();
                $normalizer->setCircularReferenceHandler(function ($nom) {
                    return $nom->getNom();
                });
                $serializer = new Serializer([$normalizer], [$encoder]);
                $formatted = $serializer->normalize($utilisateur);
                return new JsonResponse($formatted);
            }
            else
            {
                return new Response("password not found");
            }
        }
        else
        {
            return new Response("user not found");
        }}
    /**
     * @Route("/addUser", name="add_user")
     * @Method("POST")
     */

    public function ajouterUtilisateur(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $utilisateur = new Utilisateur();
        //$secret= $authenticator->generateSecret();
        //  $utilisateur->setGoogleAuthenticatorSecret($secret);
        $dateNaissance=$request->query->get("dateNaissance");
        $roles= $request->query->get("roles");
       $mdp=$request->query->get("mdp");
        $passwordEncoded = $passwordEncoder->encodePassword($utilisateur,$mdp);
        $utilisateur->setMdp($passwordEncoded);
        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $email=$request->query->get("email");
        $numTel=$request->query->get("numTel");
        $em = $this->getDoctrine()->getManager();
        if (!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            return new Response("email invalid");
        }
        $utilisateur->setNom($nom);
        $utilisateur->setPrenom($prenom);
        $utilisateur->setMdp($passwordEncoded);
        $utilisateur->setEmail($email);
        $utilisateur->setRoles(array($roles));
        $utilisateur->setDateNaissance(new \DateTime($dateNaissance));
        $utilisateur->setNumTel($numTel);



        $em->persist($utilisateur);
        $em->flush();
        /*   $serializer = new Serializer([new ObjectNormalizer()]);
          $formatted = $serializer->normalize($utilisateur);
          return new JsonResponse($formatted);*/
        return new JsonResponse("account is created",200);

    }

    /**
     * @Route ("/getPasswordByEmail", name="app_password")
     */

    public function getPasswordByEmail(Request $request)
    {
        $email=$request->get('email');
        $utilisateur= $this->getDoctrine()->getManager()->getRepository(Utilisateur::class)->findOneBy(['email'=>$email]);
        if($utilisateur)
        {
            $mdp=$utilisateur->getMdp();
            $serializer=new Serializer([new ObjectNormalizer()]);
            $formatted=$serializer->normalize($mdp);
            return new JsonResponse($formatted);
        }
        return new Response("user not found");

    }


    /**
     * @Route("/detailUser", name="detail_reclamation")
     * @Method("GET")
     */

    //Detail Reclamation
    public function affichUser(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $utilisateur= $this->getDoctrine()->getManager()->getRepository(Utilisateur::class)->find($id);
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($nom) {
            return $nom->getNom();
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($utilisateur);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/{id}/edit/guide", name="app_utilisateur_guideedit", methods={"GET", "POST"})
     */
    public function editguide2(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_utilisateur_showguide', ['id' => $session->get('id')], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/editguide.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/guide/{id}", name="app_utilisateur_showguide", methods={"GET"})
     */
    public function showguide(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/showguide.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/updateUser", name="update_user")
     * @Method("PUT")
     */
    public function modifierUser(Request $request,UserPasswordEncoderInterface $passwordEncoder) {
        $id=$request->get("id");
        $mdp=$request->get("mdp");

        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getDoctrine()->getManager()
            ->getRepository(Utilisateur::class)
            ->find($request->get("id"));

        $utilisateur->setNom($request->get("nom"));
        $utilisateur->setPrenom($request->get("prenom"));
        $utilisateur->setNumTel($request->get("numTel"));


        $utilisateur->setMdp(

            $mdp
        );


        $em->persist($utilisateur);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($utilisateur);
        return new JsonResponse("User a ete modifiee avec success.");

    }



    /**
     * @Route("/", name="app_utilisateur_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $utilisateurs = $entityManager
            ->getRepository(Utilisateur::class)
            ->findAll();

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }




    /**
     * @Route("/new", name="app_utilisateur_new", methods={"GET", "POST"})
     */
    public function new(UserPasswordEncoderInterface $encoder,Request $request, EntityManagerInterface $entityManager,\Swift_Mailer $mailer): Response
    {

        $utilisateur = new Utilisateur();
        $form = $this->createForm(FormSType::class, $utilisateur);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

           // $secret= $authenticator->generateSecret();
            //$utilisateur->setGoogleAuthenticatorSecret($secret);
            $passwordEncoded = $encoder->encodePassword($utilisateur, $form->get('mdp')->getData());
            $utilisateur->setMdp($passwordEncoded);
            $roles=$form->get('roles')->getData();
            if($roles='ROLE_CLIENT')
            {
                $utilisateur->setType("client");
            }
            elseif($roles='ROLE_GUIDE')
            {
                $utilisateur->setType("guide");
            }
            elseif($roles='ROLE_ADMIN')
            {
                $utilisateur->setType("admin");
            }
            $utilisateur->setIdInterest(5);
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            $session = $this->get('session');
            $session->set('id', $utilisateur->getId());
            $session->set('nom', $utilisateur->getNom());
            $session->set('prenom', $utilisateur->getPrenom());

////////envoi mail//////



            $message = (new \Swift_Message(' Verification email'))
                ->setFrom('eyawarteni68@gmail.com')
                ->setTo($form->get('email')->getData())

                ->setBody(
                   ' <html><body>Please click on <a href="http://127.0.0.1:8000/login">Confirm your email </a></body></html></a>',
                    'text/html'

                );
            $mailer->send($message);




           // return $this->redirectToRoute('app_utilisateur_showf', ['id' => $session->get('id')], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_utilisateur_new');

        }


        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,

            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_utilisateur_show", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
    /**
     * @Route("/user", name="app_utilisateur_showuser", methods={"GET"})
     */
    public function showuser(): Response
    {
        return $this->render('utilisateur/show.html.twig');
    }

    /**
     * @Route("/front/{id}", name="app_utilisateur_showf", methods={"GET"})
     */
    public function showf(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/user.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_utilisateur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', ['id' => $utilisateur->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/edit/front", name="app_utilisateur_useredit", methods={"GET", "POST"})
     */
    public function edit2(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_showf', ['id' => $session->get('id')], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/useredit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/front", name="app_utilisateur_delete", methods={"POST"})
     */
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/{id}", name="app_utilisateur_deletefront", methods={"POST"})
     */
    public function deletefront(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }

    /////////delete mtaa json //////
    ///
    /**
     * @Route("/json/{id}", name="app_utilisateur_deletej" )
     */


    /**
     * @Route("/{id}/verify", name="app_utilisateur_verif", methods={"POST"})
     */
    public function verif(): Response
    {



        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }






    /**
     * @Route("/json/{id}", name="app_utilisateur_deletej" )
     */
    public function deletej(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $id=$request->get("id");

        $utilisateur = $entityManager
            ->getRepository(Utilisateur::class)
            ->find($id);
        if($utilisateur!=null)
        {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
            $serialize= new Serializer([new ObjectNormalizer()]);
            $formatted=$serialize->normalize("supprime avec succes");
            return new JsonResponse($formatted);
        }

        return new JsonResponse("id invalide");


    }


}

