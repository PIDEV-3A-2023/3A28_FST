<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainBackController extends AbstractController
{
    #[Route('/main/back', name: 'app_main_back')]
    public function index(): Response
    {
        return $this->render('main_back/index.html.twig', [
            'controller_name' => 'MainBackController',
        ]);
    }

    /**
     * @Route("/stats", name="stats")
     */
    public function statistiques(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        // Transform $produits into a format compatible with Google Charts
        $data1 = [
            ['Produit', 'Likes'],
        ];

        foreach ($produits as $produit) {
            $data1[] = [$produit->getNom(), $produit->getLikes()];
        }

        // Get the count of products by category
        $categories = $produitRepository->getProductCountByCategory();

        // Transform $categories into a format compatible with Google Charts
        $data2 = [
            ['Categorie', 'Produits'],
        ];

        foreach ($categories as $categorie) {
            $data2[] = [$categorie['nom'], (int)$categorie['produit_count']];
        }

        return $this->render('main_back/stats.html.twig', [
            'data1' => $data1,
            'data2' => $data2,
        ]);
    }

}