<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GestionProduitController extends AbstractController
{
    #[Route('/gestion/produit', name: 'app_gestion_produit')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager->getRepository(Produit::class)->findAll();
        return $this->render('gestion_produit/index.html.twig', [
            'controller_name' => 'GestionProduitController',
            'produits' => $produits,
        ]);
    }


    #[Route("/delete/{id}", name:'deleteProduit')]
    public function delete($id, ManagerRegistry $doctrine)
    {$p = $doctrine
        ->getRepository(Produit::class)
        ->find($id);
        $em = $doctrine->getManager();
        $em->remove($p);
        $em->flush() ;
        return $this->redirectToRoute('app_gestion_produit');
    }

}
