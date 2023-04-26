<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use  Doctrine\Persistence\ManagerRegistry;
use App\Entity\Remise;
use App\Entity\User;
use App\Entity\Shoppingcart;

use Doctrine\ORM\EntityManagerInterface;
class DiscountController extends AbstractController
{
    #[Route('/discount', name: 'app_discount')]
    public function index(): Response
    {
        return $this->render('discount/index.html.twig', [
            'controller_name' => 'DiscountController',
        ]);
  
    }
    
    #[Route('/promo/verify', name: 'promo_verify', methods: ['GET','POST'])]
  
    public function verifyPromo(Request $request,EntityManagerInterface $em,ManagerRegistry $doctrine): Response
    {



        $promoCode = $request->request->get('promo_code');
               

        if (!is_numeric($promoCode)) {
            return new Response('Promo code must be a number.', Response::HTTP_BAD_REQUEST);
        }

        // Check if promo code exists in your database or any other source
        // You can replace this with your own code to check the promo code
        $rem= $em->getRepository(Remise::class)->find( $promoCode);
     

        if ($rem !=null ) {
            // Return a success response if promo code is valid
           
            $client = $em->getRepository(User::class)->find(1);
            $Cart   = $em->getRepository(Shoppingcart::class)->findOneBy(['user' => $client]);
            $nb = $rem->getNb() +1 ;
            $rem-> setNb($nb);
            $price =$Cart->getTotalPrice();
            $total = $price - ($price*0.2);
            $Cart->setTotalPrice($total);
            $em->persist($Cart);
            $em->persist($rem);
            $em->flush();
            return $this->redirectToRoute('app_paiement');

        } else {
            // Return an error response if promo code is invalid
            return new Response('Promo code is invalid!', Response::HTTP_BAD_REQUEST);
        }
    }
}
