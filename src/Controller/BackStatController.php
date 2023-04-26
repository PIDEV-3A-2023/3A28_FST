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

        $data1 = [
            ['Statut', 'NbrLike'],
        ];

        $sumsByType = [];

        foreach ($statuts as $statut) {
            $type = $statut->getType();
            $nbrLike = $statut->getNbrLike();

            if (isset($sumsByType[$type])) {
                $sumsByType[$type] += $nbrLike;
            } else {
                $sumsByType[$type] = $nbrLike;
            }
        }

        foreach ($sumsByType as $type => $sum) {
            $data1[] = [$type, $sum];
        }




        // Transform $produits into a format compatible with Google Charts
        $data2 = [
            ['Statut', 'Likes'],
        ];

        foreach ($statuts as $statut) {
            $data2[] = [$statut->getTitre(), $statut->getNbrLike()];
        }

        return $this->render('back_stat/stats.html.twig', [
            'data1' => $data1,
            'data2' => $data2,

        ]);
    }
}
