<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Form\AjoutprodType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $entityManager->getRepository(Produit::class)->createQueryBuilder('p');
        $searchTerm = $request->query->get('query');
    
        if ($searchTerm) {
            $queryBuilder->where('p.nom LIKE :searchTerm OR p.description LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }
    
        $query = $queryBuilder->getQuery();
    
        $pagination = $paginator->paginate(
            $query, // Query results to paginate
            $request->query->getInt('page', 1), // Current page number
            6 // Number of items per page
        );
    
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'pagination' => $pagination, // Pass the pagination object to the view
            'searchTerm' => $searchTerm, // Pass the search term to the view
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


        /**
         * @Route("/search", name="search")
         */
        public function search(Request $request): Response
        {
            $query = $request->query->get('query');

            if (!$query) {
                return $this->redirectToRoute('app_produit');
            }

            return $this->redirectToRoute('app_produit', ['query' => $query]);
        }
}




