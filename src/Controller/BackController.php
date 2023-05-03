<?php

namespace App\Controller;

use App\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EvenementRepository;
class BackController extends AbstractController
{
    #[Route('/back', name: 'app_back')]
    public function index(PaginatorInterface $paginator, Request  $request, EntityManagerInterface $entityManager, EvenementRepository $repo,): Response
    {
        $queryBuilder = $entityManager->getRepository(Evenement::class)->createQueryBuilder('e');
        $pagination = $repo->findAll();

        $query = $queryBuilder->getQuery();

        $pagination = $paginator->paginate(
            $query, // Query results to paginate
            $request->query->getInt('page', 1), // Current page number
            2// Number of items per page
        );
        return $this->render('back/index.html.twig', [
            'pagination' => $pagination,
            'list' => $pagination,
        ]);
    }
    //test
}
