<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StatutRepository;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Statut;
use App\Form\StatutType;



class DashboardStatutController extends AbstractController
{
    #[Route('/dashboard/statut', name: 'showdashboard')]
    public function index(StatutRepository $repo): Response
    {
        $statuts = $repo->findAll();
        return $this->render('dashboard_statut/index.html.twig', [
            'stat' => $statuts,

        ]);
    }
    #[Route('/dashboard/updateStatut/{id}', name: 'modifierStatut')]
    public function update(ManagerRegistry $doctrine, Request $request, $id, StatutRepository $repo): Response
    {
        $em = $doctrine->getManager();
        $statut = $doctrine->getRepository(Statut::class)->find($id);
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $statut->setUpdated(new \DateTime("now"));
            return $this->redirectToRoute('showdashboard');
        }
        return $this->renderForm('dashboard_statut/modifierstatadmin.html.twig', [
            'f' => $form,
        ]);
    }
}
