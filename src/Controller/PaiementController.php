<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Shoppingcart;
use App\Entity\User;
use  Doctrine\Persistence\ManagerRegistry;
class PaiementController extends AbstractController
{
    #[Route('/paiement', name: 'app_paiement')]
    public function index(EntityManagerInterface $em,ManagerRegistry $doctrine): Response
    {
        $client = $em->getRepository(User::class)->find(1);
 
        $shoppingcart = $doctrine
        ->getRepository(Shoppingcart::class)
        ->findOneBy(['user' => $client]);

        return $this->render('paiement/index.html.twig', [
            'controller_name' => 'PaiementController',
            'shoppingcart'=> $shoppingcart,
        ]);
    }
}
