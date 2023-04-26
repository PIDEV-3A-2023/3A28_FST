<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Commentaire;
use App\Entity\Statut;
use  Doctrine\Persistence\ManagerRegistry;
use App\Form\CommentaireType;
use App\Form\UpdateCommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\StatutRepository;
use Symfony\Component\HttpClient\HttpClient;
use Twilio\Rest\Client;



class CommentaireController extends AbstractController
{
    #[Route('/commentaire/fetch/{id}', name: 'showcommentaire')]
    public function index(CommentaireRepository $repo_c, StatutRepository $repo, $id, ManagerRegistry $doctrine, Request  $request): Response
    {
        $em = $doctrine->getManager();
        $commentaire = new Commentaire();
        $statut = $repo->find($id);
        $description = $repo_c->findByStatut($statut);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setIdS($statut);
            $commentaire->setDateAjout(new \DateTime("now"));
            //filter bad words 
            $description = $commentaire->getDescription();
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', 'https://neutrinoapi.net/bad-word-filter', [
                'query' => [
                    'content' => $description
                ],
                'headers' => [
                    'User-ID' => '22042000',
                    'API-Key' => 'WY7zvIuSAw1KTTogtS9X3IGUumCkeSHoPVOEDGPaHGd31ubF',
                ]
            ]);



            if ($response->getStatusCode() === 200) {
                $result = $response->toArray();
                if ($result['is-bad']) {
                    $sid = "ACd79f74a9650441539cb1a86910aac20f";
                    $token = "335b8b8baae1ea0e62e38a0f00886cb1";
                    $client = new Client($sid, $token);
                        $client->messages->create(
                            // The number you'd like to send the message to
                            '+21621601920',
                            [
                                // A Twilio phone number you purchased at https://console.twilio.com
                                'from' => '+16074007909',
                                // The body of the text message you'd like to send
                                'body' => "Veuillez respecter les lois de notre site, pas de gros mots!"
                            ]
                    );
              
                    // Handle bad word found
                    $this->addFlash('danger', '</i>Votre commentaire contient de gros mots, il ne peut pas etre posté.');
                    return $this->redirectToRoute('showcommentaire', ['id' => $id]);
                } else {

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($commentaire);
                    $em->flush();
                    return $this->redirectToRoute('showcommentaire', ['id' => $id]);
                }
            } else {

                return new Response("Error processing request", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }


        return $this->renderForm('commentaire/index.html.twig', [
            'stat' => $statut,
            'com' => $description,
            'f' => $form,

        ]);
    }


    #[Route('/modifier_cmt/{id}', name: 'modifier_cmt')]
    public function  modifierCom(ManagerRegistry $doctrine, $id,  Request  $request): Response
    {
        $commentaire = $doctrine
            ->getRepository(Commentaire::class)
            ->find($id);
        $form = $this->createForm(UpdateCommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('showcommentaire', ['id' => $commentaire->getIdS()->getId()]);
        }
        return $this->renderForm(
            "commentaire/modifier_cmt.html.twig",
            ["f" => $form]
        );
    }
    #[Route("/delete_cmt/{id}", name: 'supprimer_cmt')]
    public function delete($id, ManagerRegistry $doctrine)
    {
        $c = $doctrine
            ->getRepository(Commentaire::class)
            ->find($id);
        $em = $doctrine->getManager();
        $em->remove($c);
        $em->flush();
        return $this->redirectToRoute('showcommentaire', ['id' => $c->getIdS()->getId()]);
    }

    #[Route('/bad_words', name: 'bad_words')]

    function Affiche_bad(CommentaireRepository $repository)
    {
        $Commentaire = $repository->findAll();
        return $this->render('commentaire/bad_words.html.twig', ['description' => $Commentaire]);
    }
}
