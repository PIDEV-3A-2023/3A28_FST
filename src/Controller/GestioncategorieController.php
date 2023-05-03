<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\AjoutcType;
use App\Form\ModifcatType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestioncategorieController extends AbstractController
{
    #[Route('/gestioncategorie', name: 'app_gestioncategorie')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        return $this->render('gestioncategorie/index.html.twig', [
            'controller_name' => 'GestioncategorieController',
            'categories' => $categories,
        ]);
    }

    #[Route("/delete/categorie/{id}", name:'deleteCategorie')]
    public function delete($id, ManagerRegistry $doctrine)
    {$c = $doctrine
        ->getRepository(Categorie::class)
        ->find($id);
        $em = $doctrine->getManager();
        $em->remove($c);
        $em->flush() ;
        return $this->redirectToRoute('app_gestioncategorie');
    }


    #[Route ("/ajout/categorie", name : 'ajoutCategoriead')]
    public function ajoutCategorie(Request $request,EntityManagerInterface $entityManager)
    {
        $categorie = new Categorie();
        $form = $this->createForm(AjoutcType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid ()){
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_gestioncategorie');
        }
               return $this->render('gestioncategorie/ajoutCat.html.twig',[
            'form' => $form->createView(), 
        ]); 
    }

    #[Route('/update/categorie/{id}', name: 'updateCategorie')]
    public function  updateCategorie(ManagerRegistry $doctrine,$id,  Request  $request) : Response
    { $categorie = $doctrine
        ->getRepository(Categorie::class)
        ->find($id);
        $form = $this->createForm(ModifcatType::class, $categorie);
        $form->add('Modifier', SubmitType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        { $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_gestioncategorie');
        }
        return $this->renderForm("gestioncategorie/updateCat.html.twig",
            ["form"=>$form]) ;


    }
}
