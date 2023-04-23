<?php

namespace App\Controller;

use App\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentaireRepository;
use App\Repository\StatutRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentaireType;
use Knp\Component\Pager\PaginatorInterface;


class DashboardComController extends AbstractController
{
    #[Route('/dashboard/com', name: 'showdashboardcom')]
    public function index(CommentaireRepository $repo_c, PaginatorInterface $paginator, Request  $request): Response
    {

        $commentaires = $repo_c->findAll();
        $commentaires = $paginator->paginate(
            $commentaires, /* query NOT result */
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('dashboard_com/index.html.twig', [
            'com' => $commentaires,


        ]);
    }
    #[Route('/dashboard/updateCom/{id}', name: 'modifier_cmtAdmin')]
    public function update(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $em = $doctrine->getManager();
        $commentaire = $doctrine->getRepository(Commentaire::class)->find($id);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('showdashboardcom');
        }
        return $this->renderForm('dashboard_com/modifiercomAdmin.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route("/delete_cmtAdmin/{id}", name: 'supprimer_cmtAdmin')]
    public function delete($id, ManagerRegistry $doctrine)
    {

        $em = $doctrine->getManager();
        $commentaire = $doctrine->getRepository(Commentaire::class)->find($id);

        $em->remove($commentaire);
        $em->flush();
        return $this->redirectToRoute('showdashboardCom');
    }
}
