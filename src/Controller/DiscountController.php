<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
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
  
    public function verifyPromo(Request $request): Response
    {
        $promoCode = $request->request->get('promo_code');
        if (!is_numeric($promoCode)) {
            return new Response('Promo code must be a number.', Response::HTTP_BAD_REQUEST);
        }

        // Check if promo code exists in your database or any other source
        // You can replace this with your own code to check the promo code
        $isValid = $promoCode === 'VALIDCODE';

        if ($isValid) {
            // Return a success response if promo code is valid
            return new Response('Promo code is valid!');
        } else {
            // Return an error response if promo code is invalid
            return new Response('Promo code is invalid!', Response::HTTP_BAD_REQUEST);
        }
    }
}
