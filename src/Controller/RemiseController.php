<?php

namespace App\Controller;
use App\Entity\Remise;
use App\Form\RemiseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use  Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class RemiseController extends AbstractController
{
    #[Route('/remise', name: 'app_remise')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $remise = $entityManager->getRepository(Remise::class)->findAll();
        return $this->render('remise/index.html.twig', [
            'controller_name' => 'RemiseController',
            'remise'=> $remise,
        ]);
    }

    #[Route('/addremise', name: 'addremise')]
    public function  AddRemise(ManagerRegistry $doctrine, Request  $request) : Response
    { $remise = new Remise() ;
        $form = $this->createForm(RemiseType::class, $remise);
 
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $em = $doctrine->getManager();
            $em->persist($remise);
            $em->flush();
            return $this->redirectToRoute('app_remise'); 
        }
        return $this->renderForm("remise/addRemise.html.twig",
            ["form"=>$form]) ;
    }
    
    #[Route("/delete/{id}", name:'deleteRemise')]
    public function delete($id, ManagerRegistry $doctrine)
    {$p = $doctrine
        ->getRepository(Remise::class)
        ->find($id);
        $em = $doctrine->getManager();
        $em->remove($p);
        $em->flush() ;
        return $this->redirectToRoute('app_remise');
    }

    #[Route('/update/{id}', name: 'updateRemise')]
    public function  updateProduit(ManagerRegistry $doctrine,$id,  Request  $request) : Response
    { $remise = $doctrine
        ->getRepository(Remise::class)
        ->find($id);
        $form = $this->createForm(RemiseType::class, $remise);
      
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        { $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_remise');
        }
        return $this->renderForm("remise/update.html.twig",
            ["form"=>$form]) ;


    }


}
