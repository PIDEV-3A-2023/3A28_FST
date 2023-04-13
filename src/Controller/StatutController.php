<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\StatutRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Statut;
use App\Form\StatutType;

use Symfony\Component\Routing\Annotation\Route;

class StatutController extends AbstractController
{
    #[Route('/statut/fetch', name: 'showstatut')]
    public function index(StatutRepository $repo): Response
    {
        $statuts = $repo->findAll();
        return $this->render('statut/index.html.twig', [
            'stat' => $statuts,
        ]);
    }

    #[Route('/statut/add', name: 'addstatut')]
    public function addstatut(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $statut = new Statut();
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $statut->setNbrLike(0);
            $statut->setCreated(new \DateTime("now"));
            $em->persist($statut);
            $em->flush();
            return $this->redirectToRoute('showstatut');
        }
        return $this->renderForm('statut/ajoutstatut.html.twig', [
            'f' => $form,
        ]);
    }

    #[Route('/statut/remove/{id}', name: 'removestatut')]
    public function remove(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $statut = $doctrine->getRepository(Statut::class)->find($id);

        $em->remove($statut);
        $em->flush();
        return $this->redirectToRoute('showstatut');
    }

    #[Route('/statut/update/{id}', name: 'updatestatut')]
    public function update(ManagerRegistry $doctrine, Request $request, $id, StatutRepository $repo): Response
    {
        $em = $doctrine->getManager();
        $statut = $doctrine->getRepository(Statut::class)->find($id);
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $statut->setUpdated(new \DateTime("now"));
            return $this->redirectToRoute('showstatut');
        }
        return $this->renderForm('statut/modifierstatut.html.twig', [
            'f' => $form,
        ]);
    }
}
