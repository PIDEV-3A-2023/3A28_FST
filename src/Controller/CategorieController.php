<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Form\AjoutcType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

         /**
     * @param Request $request
     * @Route("ajoutCategorie", name="ajoutCategorie")
     */
    public function ajoutProduit(Request $request,EntityManagerInterface $entityManager)
    {
        $categorie = new Categorie();
        $form = $this->createForm(AjoutcType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid ()){
            $entityManager->persist($categorie);
            $entityManager->flush();

            $this->addFlash('notice','ajouter avec succées!');

            return $this->redirectToRoute('app_produit');
        }
               return $this->render('categorie/ajoutCategorie.html.twig',[
            'form' => $form->createView(), 
        ]); 
    }
}
