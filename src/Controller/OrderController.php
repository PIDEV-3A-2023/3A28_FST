<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use  Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Shoppingcart;
use App\Entity\User;
use App\Entity\Cartitem;
use App\Form\ShoppingcartType;

class OrderController extends AbstractController
{
    #[Route('/AppOrder', name: 'App_Ordre')]
    public function index(EntityManagerInterface $em,ManagerRegistry $doctrine,  Request  $request) : Response
    { 
        $client = $em->getRepository(User::class)->find(1);
 
        $shoppingcart = $doctrine
        ->getRepository(Shoppingcart::class)
        ->findOneBy(['user' => $client]);
        $form = $this->createForm(ShoppingcartType::class, $shoppingcart);
      
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        { $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_gestion_order');
        }
        return $this->render('order/index.html.twig',   ["form"=>$form]);
    }



    public function somme(array $cartitems): float
    { 
        $total = array_reduce($cartitems, function($accumulator, $item) {
            return $accumulator + $item->getTotal();
        }, 0);
        return $total;
    }

    #[Route('/order', name: 'Ordre')]
    public function AddOrder(EntityManagerInterface $em,ManagerRegistry $doctrine,  Request  $request) : Response
    { 
        $client = $em->getRepository(User::class)->find(1);
 
        $shoppingcart = $doctrine
        ->getRepository(Shoppingcart::class)
        ->findOneBy(['user' => $client]);

        $cartitem = $em
        ->getRepository(Cartitem::class)
        ->findBy(['panier' => $shoppingcart]);



        $totalprice = $this->somme($cartitem); 
        $shoppingcart->setTotalPrice( $totalprice);
        $em = $doctrine->getManager();
        $em->persist( $shoppingcart);
        $em->flush();




        $form = $this->createForm(ShoppingcartType::class, $shoppingcart);
      
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        { $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_paiement');
        }
        return $this->render('order/addOrder.html.twig', ["form" => $form->createView()]);

    }





 
}
