<?php

namespace App\Controller;

use App\Entity\Interest;
use App\Entity\Matching;
use App\Entity\Utilisateur;
use App\Form\MatchingType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/matching")
 */
class MatchingController extends AbstractController
{

    /**
     * @Route("/pdf", name="pdf")
     */
    public function pdf(EntityManagerInterface $entityManager)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $matching = $entityManager
            ->getRepository(Matching::class)
            ->findAll();


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('matching/pdf.html.twig', [
            'matching' => $matching
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }





    /**
     * @Route("/", name="app_matching_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $session = $request->getSession();
        $uss=$this->getUser();

        $user = $entityManager
            ->getRepository(Utilisateur::class)
            ->findAll();

        $userconn = $entityManager
            ->getRepository(Utilisateur::class)
            ->find($uss->getSalt());

        $interestconn = $entityManager
            ->getRepository(Interest::class)
            ->find($userconn->getIdInterest());

        $interest = $entityManager
            ->getRepository(Interest::class)
            ->findAll();
        return $this->render('matching/index.html.twig', [
            'users' => $user,
            'interst'=>$interest,
            'interstconn'=>$interestconn
        ]);
    }


    /**
     * @Route("/matchingAdmin", name="adminMatching", methods={"GET"})
     */
    public function indexAdmin(EntityManagerInterface $entityManager,Request $request, PaginatorInterface $paginator): Response
    {

        $matchingsall = $entityManager
            ->getRepository(Matching::class)
            ->findAll();

        $matching = $paginator->paginate(
            $matchingsall, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
             3// Nombre de résultats par page
        );
        return $this->render('matching/indexaDMIN.html.twig', [
            'matching' => $matching,
        ]);
    }


    /**
     * @Route("/Mylist", name="mylisT", methods={"GET"})
     */
    public function matchingList(EntityManagerInterface $entityManager,Request $request): Response
    {
        $session = $request->getSession();
        $uss=$this->getUser();

        $user = $entityManager
            ->getRepository(Matching::class)
            ->find($uss->getSalt());
        $matching = $entityManager
            ->getRepository(Matching::class)
            ->findBy(array('client1'=>$uss->getSalt()));
        return $this->render('matching/myList.html.twig', [
            'users' => $user,
            'matchings'=>$matching
        ]);
    }

    /**
     * @Route("/new", name="app_matching_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $matching = new Matching();
        $form = $this->createForm(MatchingType::class, $matching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($matching);
            $entityManager->flush();

            return $this->redirectToRoute('app_matching_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('matching/new.html.twig', [
            'matching' => $matching,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idMatch}", name="app_matching_show", methods={"GET"})
     */
    public function show(Matching $matching): Response
    {
        return $this->render('matching/show.html.twig', [
            'matching' => $matching,
        ]);
    }


    /**
     * @Route("/delete/{id}", name="app_matching_delete")
     */
    public function delete($id,\Swift_Mailer $mailer,EntityManagerInterface $entityManager,Request $request): Response
    {
        $session = $request->getSession();
        $uss=$this->getUser();

        $users = $entityManager
            ->getRepository(Utilisateur::class)
            ->find($uss->getSalt());
        $matching = $entityManager
            ->getRepository(Matching::class)
            ->find($id);
        $client2 = $entityManager
            ->getRepository(Utilisateur::class)
            ->find($matching->getClient2()->getId());

        $em = $this->getDoctrine()->getManager();
        $match = $em->getRepository(Matching::class)->find($id);
        $em->remove($match);
        $em->flush();
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('mootez.mejdoub@esprit.tn')
            ->setTo($client2->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'matching/emailDeleteMatch.html.twig',
                    ['client' => $client2, 'user'=>$users
                    ]
                ),
                'text/html'
            )

            // you can remove the following code if you don't define a text version for your emails
            ->addPart(
                $this->renderView(
                // templates/emails/registration.txt.twig
                    'matching/emailDeleteMatch.html.twig',
                    ['client' => $client2, 'user'=>$users]
                ),
                'text/plain'
            )
        ;

        $mailer->send($message);


        return $this->redirectToRoute('mylisT');
    }


    /**
     * @Route("/matching/{id}", name="addmatching")
     */
    public function addMatchin(Request $request,$id,EntityManagerInterface $entityManager, \Swift_Mailer $mailer)
    {
        $session = $request->getSession();
$uss=$this->getUser();
        $users = $entityManager
            ->getRepository(Utilisateur::class)
            ->find($uss->getSalt());
        $client2 = $entityManager
            ->getRepository(Utilisateur::class)
            ->find($id);


        //$this->getUser();
        $matching = new Matching();
        $matching->setClient1($users);
        $matching->setClient2($client2);

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('mootez.mejdoub@esprit.tn')
            ->setTo($client2->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'matching/emailnewMatch.html.twig',
                    ['client' => $client2, 'user'=>$users
                    ]
                ),
                'text/html'
            )

            // you can remove the following code if you don't define a text version for your emails
            ->addPart(
                $this->renderView(
                // templates/emails/registration.txt.twig
                    'matching/emailnewMatch.html.twig',
                    ['client' => $client2, 'user'=>$users]
                ),
                'text/plain'
            )
        ;

        $mailer->send($message);


        $entityManager->persist($matching);
        $entityManager->flush();


        return $this->redirectToRoute('mylisT', [], Response::HTTP_SEE_OTHER);

    }





}
