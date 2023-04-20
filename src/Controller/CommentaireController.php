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


class CommentaireController extends AbstractController
{
    #[Route('/commentaire/fetch/{id}', name: 'showcommentaire')]
    public function index(CommentaireRepository $repo_c, StatutRepository $repo, $id, ManagerRegistry $doctrine, Request  $request): Response
    {
        $em = $doctrine->getManager();
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $statut = $repo->find($id);
        $commentaires = $repo_c->findByStatut($statut);
        $last = $repo->latest_posts();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setDateAjout(new \DateTime("now"));
            $commentaire->setIdS($statut);
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('showcommentaire', ['id' => $id]);
        }


        return $this->renderForm('commentaire/index.html.twig', [
            'stat' => $statut,
            'com' => $commentaires,
            'f' => $form,
            'last' => $last

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
}
