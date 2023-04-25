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
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Doctrine\ORM\EntityManagerInterface;


class DashboardStatutController extends AbstractController
{
    #[Route('/dashboard/statut', name: 'showdashboard')]
    public function index(EntityManagerInterface $entityManager, StatutRepository $repo , PaginatorInterface $paginator, Request  $request): Response
    {
        $queryBuilder = $entityManager->getRepository(Statut::class)->createQueryBuilder('s');
        $pagination = $repo->findAll();

        $query = $queryBuilder->getQuery();

        $pagination = $paginator->paginate(
            $query, // Query results to paginate
            $request->query->getInt('page', 1), // Current page number
            5 // Number of items per page
        );
       
        return $this->render('dashboard_statut/index.html.twig', [
            'stat' => $pagination,
            'pagination' => $pagination, 


        ]);
    }
    #[Route('/dashboard/modifierStatut/{id}', name: 'modifierStatut')]
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
    #[Route("/dashboard/removeStatut/{id}", name: 'removeStatut')]
    public function delete($id, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $statut = $doctrine->getRepository(Statut::class)->find($id);

        $em->remove($statut);
        $em->flush();
        return $this->redirectToRoute('showdashboard');
    }
}
