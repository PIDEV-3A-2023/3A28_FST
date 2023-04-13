<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Form\ModifproduitType;
use App\Form\AjoutprodType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    #[Route('/update/{id}', name: 'updateProduit')]
    public function  updateProduit(ManagerRegistry $doctrine,$id,  Request  $request) : Response
    { $produit = $doctrine
        ->getRepository(Produit::class)
        ->find($id);
        $form = $this->createForm(ModifproduitType::class, $produit);
        $form->add('update', SubmitType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        { $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_gestion_produit');
        }
        return $this->renderForm("gestion_produit/update.html.twig",
            ["form"=>$form]) ;


    }


        #[Route('/ajout/produitad', name: 'ajoutProduitad')]
        public function ajoutProduit(Request $request, EntityManagerInterface $entityManager)
        {
            $produit = new Produit();
            $form = $this->createForm(AjoutprodType::class, $produit);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $images = $form->get('image')->getData();
                foreach ($images as $image) {
                    if ($image instanceof UploadedFile) {
                        $fileName = md5(uniqid()) . '.' . $image->guessExtension();
                        $image->move(
                            $this->getParameter('images_directory'),
                            $fileName
                        );
                        $produit->setImage($fileName);
                    }
                }

                $entityManager->persist($produit);
                $entityManager->flush();

                return $this->redirectToRoute('app_gestion_produit');
            }

            return $this->render('gestion_produit/ajoutprod.html.twig', [
                'form' => $form->createView(),
            ]);
        }

}
