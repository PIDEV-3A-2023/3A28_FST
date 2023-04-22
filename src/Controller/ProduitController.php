<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Form\AjoutprodType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;


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
        $image = $form->get('image')->getData();
        if ($image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $this->generateSafeFilename($originalFilename).'.'.$image->guessExtension();
            try {
                $image->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle the exception
            }
            $produit->setImage($newFilename);
        }

        $entityManager->persist($produit);
        $entityManager->flush();

        $this->addFlash('success', 'Produit ajouté avec succès!');

        return $this->redirectToRoute('app_produit');
    }

    return $this->render('produit/ajoutProduit.html.twig', [
        'form' => $form->createView(),
    ]);
}

        private function generateSafeFilename($originalFilename)
        {
            // Remove any non-alphanumeric characters from the filename
            $filename = preg_replace('/[^a-zA-Z0-9]/', '-', $originalFilename);

            // Remove any consecutive dashes
            $filename = preg_replace('/-{2,}/', '-', $filename);

            // Remove any leading or trailing dashes
            $filename = trim($filename, '-');

            // Add a unique ID to the filename to ensure it's unique
            $newFilename = $filename.'-'.uniqid();

            return $newFilename;
        }
}




