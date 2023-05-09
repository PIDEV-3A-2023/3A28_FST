<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Feedback;
use App\Form\FeedbackType;
use App\Repository\EvenementRepository;
use App\Repository\FeedbackRepository;

use  Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

class FeedbackController extends AbstractController
{

    #[Route('/feedback/{id}', name: 'feedback')]
    public function index(FeedbackRepository $repo_f, EvenementRepository $repo, $id, ManagerRegistry $doctrine, Request  $request): Response
    {
        $em = $doctrine->getManager();
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $event = $repo->find($id);
        $feedbacks = $repo_f->findByEvent($event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $feedback->setIdEv($event);
            $em->persist($feedback);
            $em->flush();
            return $this->redirectToRoute('feedback', ['id' => $id]);
        }


        return $this->renderForm('feedback/index.html.twig', [
            'list' => $feedback->getIdEv(),
            'feed' => $feedbacks,
            'f' => $form
        ]);
    }
    #[Route('/updatef/{id}', name: 'updatef')]
    public function update(Request $request, $id)
    {
        $feedback = $this->getDoctrine()->getRepository(Feedback::class)->find($id);
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();
            $this->addFlash('notice', 'Submitted');
        }
        return $this->render('feedback/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/deletef/{id}', name: 'deletef')]
    public function delete($id)
    {
        $data = $this->getDoctrine()->getRepository(Feedback::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();
        $this->addFlash('notice', ' delete Submitted');
        return $this->redirectToRoute('feedback', ['id' => $data->getIdEv()->getId()]);
    }
    ////JSOOOON////
    #[Route('/Allfeedback/{id}', name: 'feedback')]
    public function allFeed(FeedbackRepository $repo_f, EvenementRepository $repo, $id, SerializerInterface $serializer): Response
    {
        $event = $repo->find($id);
        $feedbacks = $repo_f->findByEvent($event);
        $json = $serializer->serialize($feedbacks, 'json', ['groups' => "feedbacks"]);

        return new Response($json);
    }
}
