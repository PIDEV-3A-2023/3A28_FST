<?php

namespace App\Controller;
use App\Entity\Cartitem;
use App\Entity\User;
use App\Entity\Produit;
use  Doctrine\Persistence\ManagerRegistry;
use App\Entity\Shoppingcart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(EntityManagerInterface $entityManager ,SessionInterface $session): Response
    {
        $client = $entityManager->getRepository(User::class)->find(1);
        $panierparclient = $entityManager->getRepository(Shoppingcart::class)->findOneBy(['user' => $client]);
          $cartitem = $entityManager
        ->getRepository(Cartitem::class)
        ->findBy(['panier' => $panierparclient]);
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'cartitem' => $cartitem, 
        ]);
    }

    #[Route('/plus/{id}', name: 'app_ligne_panier_plus', methods: ['GET','POST'])]
    public function addByOne(Request $request, Cartitem $lignePanier, EntityManagerInterface $entityManager,SessionInterface $session):Response
    {       $quantity=$lignePanier->getQuantity()+1;
          $total=($lignePanier->getPrice())*$quantity;
            $lignePanier->setQuantity($quantity);
            $lignePanier->setTotal($total);
            $entityManager->persist($lignePanier);
        
            $entityManager->flush();

           return $this->redirectToRoute('app_panier');
    }

    #[Route('/minus/{id}', name: 'app_ligne_panier_minus', methods: ['GET','POST'])]
    public function minusByOne(Request $request, Cartitem $lignePanier, EntityManagerInterface $entityManager,SessionInterface $session):Response
    {   if($lignePanier->getQuantity()>1) {
        $quantity=$lignePanier->getQuantity() - 1;
        $total=($lignePanier->getPrice())*$quantity;
        $lignePanier->setQuantity($quantity);
        $lignePanier->setTotal($total);
        $entityManager->persist($lignePanier);
        $entityManager->flush();
    
       return $this->redirectToRoute('app_panier');
    }
        return new JsonResponse(['success' => false]);
    }






    #[Route('/Add/{id}', name: 'added_to_cart', methods: ['GET','POST'])]
    public function AddToCart(Request $request,$id, EntityManagerInterface $entityManager,ManagerRegistry $doctrine):Response
    {    
     
        $client = $entityManager->getRepository(User::class)->find(1);
        $panierparclient = $entityManager->getRepository(Shoppingcart::class)->findOneBy(['user' => $client]);
             
        $article = $entityManager->getRepository(Produit::class)->find($id);
        
        $cartitem = $entityManager
        ->getRepository(Cartitem::class)
        ->findBy(['panier' => $panierparclient]);
        $test = TRUE; 
        
       
        foreach ($cartitem as $item) {
            if ($item->getProduit()->getId() == $id) {
                $test = false;
                $Cart = $item;
                break;
            }
        }
        
 

        
        if ($test == TRUE ){
         $lignePanier = new Cartitem();
        $lignePanier->setQuantity(1);
        $lignePanier->setPanier($panierparclient);  
        $lignePanier->setProduit($article); 
        $lignePanier->setPrice($article->getPrix());
        $lignePanier->setTotal($article->getPrix());
       // $totalprice =
       // $panierparclient->setTotalPrice($totalprice)
        $entityManager = $doctrine->getManager();
        $entityManager->persist($lignePanier);
        $entityManager->flush();
        
       return $this->redirectToRoute('app_panier');}
       else {
      
      
        $quantity=$Cart->getQuantity() + 1;
        $total=($Cart->getPrice())*$quantity;
        $Cart->setQuantity($quantity);
        $Cart->setTotal($total);
        $entityManager->persist($Cart);
        $entityManager->flush();
        return $this->redirectToRoute('app_panier');
       }
    
   
    }



}