<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Form\ReclamationType;
use App\Repository\BlogRepository;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ReCaptcha\ReCaptcha; // Include the recaptcha lib
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twilio\Rest\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController
{


    // /******* /*RECLAMATION JSON** */


    /**
     * @Route("/displayReclamations", name="display_reclamation")
     */
    public function allRecAction()
    {

        $reclamation = $this->getDoctrine()->getManager()->getRepository(Reclamation::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);

        return new JsonResponse($formatted);

    }
    ///******************Ajouter Reclamation*****************************************/
    /**
     * @Route("/addReclamation", name="add_reclamation")
     * @Method("POST")
     */

    public function ajouterrReclamationAction(Request $request,EntityManagerInterface $entityManager)
    {
        $reclamation = new Reclamation();
        $description = $request->query->get("description");
        $id=$request->query->get("id");
        $user=$entityManager->getRepository(Utilisateur::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');

        $reclamation->setDescription($description);
        $reclamation->setDate($date);
        $reclamation->setEtat('en attente');
        $reclamation->setRecReference( $random = random_bytes(10));
        $reclamation->setUser($user);
        $em->persist($reclamation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse($formatted);
    }
    /******************Supprimer Reclamation*****************************************/

    /**
     * @Route("/deleteReclamation", name="delete_reclamation")
     * @Method("DELETE")
     */

    public function deleteReclamationAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        if($reclamation!=null ) {
            $em->remove($reclamation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reclamation invalide.");


    }
    /******************Modifier Reclamation*****************************************/
    /**
     * @Route("/updateReclamation", name="update_reclamation")
     * @Method("PUT")
     */
    public function modifierReclamationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getManager()
            ->getRepository(Reclamation::class)
            ->find($request->get("id"));


        $reclamation->setDescription($request->get("description"));

        $em->persist($reclamation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse("Reclamation a ete modifiee avec success.");

    }
    /******************Detail Reclamation*****************************************/

    /**
     * @Route("/detailReclamation", name="detail_reclamation")
     * @Method("GET")
     */

    //Detail Reclamation
    public function detailReclamationAction(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getManager()->getRepository(Reclamation::class)->find($id);
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getDescription();
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse($formatted);
    }


    ///////////////////////////*/



    /**
     * @Route("/", name="app_reclamation_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,ReclamationRepository $rep): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();
            /*
            $rep->findByExampleField(51);
        */
        $reclamationEAtt=$rep->countEnAttent();
        $reclamationTraite=$rep->counttraite();
        $reclamationTot=$rep->countall();


        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
            'reclamationEAtt'=>$reclamationEAtt,
            'reclamationTraite'=>$reclamationTraite,
            'reclamationTot'=>$reclamationTot,

        ]);
    }

    /**
     * @Route("/new", name="app_reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,ReclamationRepository $rep,\Swift_Mailer $mailer,FlashyNotifier $flashy): Response
    {
        $sid    = "AC11504623102e397beac52ffbea6fef4d";
        $token  = "bda3355dce262c9246f1a90f0ce7611f";
        $twilio = new Client($sid, $token);

        $session = $request->getSession();
        $uss=$this->getUser();

        $user=new Utilisateur();
        $user=$entityManager->getRepository(Utilisateur::class)->find($uss->getSalt());
        $random = random_bytes(8);

        $reclamation = new Reclamation();
        $rec=new Reclamation();
        $rec=$rep->findByExampleField($uss->getSalt());



        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($_POST['submit'])) {
                $secret = "6Ldwe3kfAAAAABR7mqR1DkVXIqsjvzo5LPLvhSQo";
                $response = $_POST['g-recaptcha-response'];
                $remoteip = $_SERVER['REMOTE_ADDR'];
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
                $data = file_get_contents($url);
                $row = json_decode($data, true);
                if ($row['success'] == "true") {

                    $reclamation->setUser($user);
                    $reclamation->setEtat('en attent');
                    $reclamation->setDate(new \DateTime('now'));
                    $reclamation->setRecReference($random);
                    $message = $twilio->messages
                        ->create("whatsapp:+21627066310", // to
                            array(
                                "from" => "whatsapp:+14155238886",
                                "body" => $user->getNom()." a envoyee une Reclamation , vous pouvez le contacter sur ".$user->getNumTel(),
                            )
                        );

                    $entityManager->persist($reclamation);
                    $entityManager->flush();





                    $msg = (new \Swift_Message('Reclamation ♥'))
                        ->setFrom('mootez.mejdoub@esprit.tn')
                        ->setTo($user->getEmail())
                        ->setBody('Votre demande sera prise en compte et nous vous répondrons dans les meilleurs délais.
 Vous serez notifiés via une maill les details de traitement de votre reclamation
 Merci !!');
                    $mailer->send($msg);

                    $flashy->success('Reclamation envoyee !');

                    return $this->redirectToRoute('app_reclamation_new', [], Response::HTTP_SEE_OTHER);
                }
                else


                $flashy->error('Captcha is Required!');
            }
        }



        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
            'rec'=>$rec,
            'user'=>$user,
        ]);
    }

    /**
     * @Route("/{id}", name="app_reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {

        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager,$id): Response
    {

        $reclamation=$entityManager->getRepository(Reclamation::class)->find($id);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/delete/{id}/", name="app_reclamation_deleteClient")
     */
    public function deleteClient(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager,$id): Response
    {

            $reclamation=$entityManager->getRepository(Reclamation::class)->find($id);
            $entityManager->remove($reclamation);
            $entityManager->flush();


        return $this->redirectToRoute('app_reclamation_new', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/stats/admin", name="app_reclamation_stats")
     */
    public function stats(EntityManagerInterface $entityManager,ReclamationRepository $rep,BlogRepository $blogrep){
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        $reclamationEAtt=$rep->countEnAttent();
        $reclamationTraite=$rep->counttraite();
        $reclamationTot=$rep->countall();
        $sousse=$blogrep->countSousse();
        $bizerte=$blogrep->countBizerte();
        $tozeur=$blogrep->countTozeur();
        $kef=$blogrep->countKef();
        $mehdia=$blogrep->countMahdia();
        $nabeul=$blogrep->countNabeul();

        return $this->render('reclamation/statistics.html.twig', [
            'reclamations' => $reclamations,
            'reclamationEAtt'=>$reclamationEAtt,
            'reclamationTraite'=>$reclamationTraite,
            'reclamationTot'=>$reclamationTot,
            'mehdia'=> $mehdia,
            'kef'=>$kef,
            'tozeur'=>$tozeur,
            'bizerte'=>$bizerte,
            'nabeul'=>$nabeul,
            'sousse'=>$sousse,


        ]);
    }


    /**
     * @Route("/listePdf/download",name="reclamation_listePdf")
     */
    public function listePdf(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reclamation/listePdf.html.twig', [
            'reclamations' => $reclamations,
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
        return $this->render('reclamation/listePdf.html.twig', [
            'reclamations' => $reclamations,


        ]);



    }




}
