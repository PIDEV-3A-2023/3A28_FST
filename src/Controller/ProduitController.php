<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Form\AjoutprodType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;


class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager->getRepository(Produit::class)->findAll();
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produits' => $produits, // Pass the products to the view
        ]);
    }

/**
 * @param Request $request
 * @Route("ajoutProduit", name="ajoutProduit")
 */
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

        $this->addFlash('notice', 'ajouter avec succées!');

        return $this->redirectToRoute('app_produit');
    }

    return $this->render('produit/ajoutProduit.html.twig', [
        'form' => $form->createView(),
    ]);
}


}

