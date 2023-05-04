<?php

namespace App\Controller;
use App\Entity\Shoppingcart;
use App\Entity\Cartitem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use  Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;

use App\Form\OrderType;
class GestionOrderController extends AbstractController
{
    #[Route('/gestion/order', name: 'app_gestion_order')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $shoppingcart = $entityManager->getRepository(Shoppingcart::class)->findAll();
        return $this->render('gestion_order/index.html.twig', [
            'controller_name' => 'GestionOrderController',
            'shoppingcart'=> $shoppingcart,
           
        ]);
    }
    #[Route('/addOrder', name: 'addOrder')]
    public function  AddRemise(ManagerRegistry $doctrine, Request  $request) : Response
    { $shoppingcart = new Shoppingcart() ;
        $form = $this->createForm(OrderType::class, $shoppingcart);
 
        $form->handleRequest($request);
        if (($form->isSubmitted() && $form->isValid()))
        { $em = $doctrine->getManager();
            $em->persist($shoppingcart);
            $em->flush();
            return $this->redirectToRoute('app_gestion_order'); 
        }
        return $this->renderForm("gestion_order/addOrder.html.twig",
            ["form"=>$form]) ;
    }
    
    #[Route("/deleteOrder/{id}", name:'deleteOrder')]
    public function delete($id, ManagerRegistry $doctrine)
    {$p = $doctrine
        ->getRepository(Shoppingcart::class)
        ->find($id);
        $em = $doctrine->getManager();
        $em->remove($p);
        $em->flush() ;
        return $this->redirectToRoute('app_gestion_order');
    }
    #[Route('/updateOrdre/{id}', name: 'updateOrdre')]
    public function  updateProduit(ManagerRegistry $doctrine,$id,  Request  $request) : Response
    { $shoppingcart = $doctrine
        ->getRepository(Shoppingcart::class)
        ->find($id);
        $form = $this->createForm(OrderType::class, $shoppingcart);
      
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        { $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_gestion_order');
        }
        return $this->renderForm("gestion_order/updateOrder.html.twig",
            ["form"=>$form]) ;


    }
    #[Route('/detailsee/{id}', name: 'detailsee')]
    public function ShowDetails($id ,EntityManagerInterface $entityManager)
    {
        $shoppingcart = $entityManager->getRepository(Shoppingcart::class)->find($id);
      
        return $this->render('gestion_order/details.html.twig', [
            'controller_name' => 'GestionOrderController',
          
            "cartitem"=>$shoppingcart->getOrderDetails(),
        ]);
    }






    #[Route('/showPdf/{id}', name: 'showPdf')]
    public function showPdf($id ,EntityManagerInterface $entityManager): Response
    {
        $order = $entityManager->getRepository(Shoppingcart::class)->find($id);
      
         // Render the Twig template to HTML
         $html = $this->renderView('gestion_order/pdf.html.twig', [
            // Pass any required data to the template
            'data' => $order
        ]);
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
    
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');
    
        // Render the HTML as PDF
        $dompdf->render();
    
        // Output the generated PDF as response
        $pdfContent = $dompdf->output();
        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
    
        return $response;
    
    }

}

