<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Shoppingcart;
use App\Entity\User;
use Stripe\Stripe;
use  Doctrine\Persistence\ManagerRegistry;


use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(SessionInterface $session1,ManagerRegistry $doctrine,EntityManagerInterface $em,): Response //we go to stripe checkout url
    {
        Stripe::setApiKey('sk_test_51Mhso8JUWt71NA3D0Xfp4L2IrdqJuTSymxdex0kR8NYbwiUCeTJL1fybK9Hj7p9msIFb3aGVjWsHGYhg4trH4Jm800im117qdR');
        $client = $em->getRepository(User::class)->find(1);
 
        $shoppingcart = $doctrine
        ->getRepository(Shoppingcart::class)
        ->findOneBy(['user' => $client]);
     
        $total = $shoppingcart->getTotalPrice();



        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => 'Total Amount :',
                        ],
                        'unit_amount'  => $total*100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
 
}

#[Route('/success-url', name: 'success_url')]
public function successUrl(SessionInterface $session1,EntityManagerInterface $entityManager): Response
{
  

    return $this->render('paiement/success.html.twig', []);
}


#[Route('/cancel-url', name: 'cancel_url')]
public function cancelUrl(): Response
{
    return $this->render('cancel.html.twig', []);
}
}