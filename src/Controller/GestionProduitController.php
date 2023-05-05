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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface;


class GestionProduitController extends AbstractController
{
    #[Route('/gestion/produit', name: 'app_gestion_produit')]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $query = $entityManager->getRepository(Produit::class)->createQueryBuilder('p')->getQuery();


        $produits = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('gestion_produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }


    #[Route("/deleteProduit/{id}", name: 'deleteProduit')]
    public function deleteProduit($id, ManagerRegistry $doctrine)
    {
        $p = $doctrine
            ->getRepository(Produit::class)
            ->find($id);
        $em = $doctrine->getManager();
        $em->remove($p);
        $em->flush();
        return $this->redirectToRoute('app_gestion_produit');
    }

    #[Route('/updateProduit/{id}', name: 'updateProduit')]
    public function  updateProduit(ManagerRegistry $doctrine, $id,  Request  $request): Response
    {
        $produit = $doctrine
            ->getRepository(Produit::class)
            ->find($id);
        $form = $this->createForm(ModifproduitType::class, $produit);
        $form->add('Modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_gestion_produit');
        }
        return $this->renderForm(
            "gestion_produit/update.html.twig",
            ["form" => $form]
        );
    }


    #[Route('/ajout/produitad', name: 'ajoutProduitad')]
    public function ajoutProduit(Request $request, EntityManagerInterface $entityManager)
    {
        $produit = new Produit();
        $form = $this->createForm(AjoutprodType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $this->generateSafeFilename($originalFilename) . '.' . $image->guessExtension();
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
        }

        return $this->render('gestion_produit/ajoutprod.html.twig', [
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
        $newFilename = $filename . '-' . uniqid();

        return $newFilename;
    }
}
