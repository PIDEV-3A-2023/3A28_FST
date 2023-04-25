<?php

namespace App\Controller;

use App\Entity\Statut;
use App\Repository\StatutRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackStatController extends AbstractController
{
    #[Route('/back/stat', name: 'app_back_stat')]


    public function statistiques(StatutRepository $rep): Response
    {
        $statuts = $rep->findAll();

        // Transform $produits into a format compatible with Google Charts

        $data1 = [
            ['Statut', 'NbrLike'],
        ];

        foreach ($statuts as $statut) {
            $data1[] = [$statut->getType(), $statut->getNbrLike()];
        }

        return $this->render('back_stat/stats.html.twig', [
            'data1' => $data1,
        ]);
    }
}
