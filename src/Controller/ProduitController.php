<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Form\AjoutprodType;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;



class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator, CategorieRepository $Catrepo ): Response
    {
        $queryBuilder = $entityManager->getRepository(Produit::class)->createQueryBuilder('p');
        $searchTerm = $request->query->get('query');
        $sortBy = $request->query->get('sort_by');
        $selectedCategories = $request->query->get('categories', []);
        $categories = $Catrepo ->findall ();


        if (!empty($selectedCategories)) {
            $queryBuilder->andWhere('p.id_ctg IN (:selectedCategories)');
            $queryBuilder->setParameter('selectedCategories', $selectedCategories);
        }
    
        if ($searchTerm) {
            $queryBuilder->where('p.nom LIKE :searchTerm OR p.description LIKE :searchTerm OR p.prix LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }
        if ($sortBy === 'price_asc') {
            $queryBuilder->orderBy('p.prix', 'ASC');
        } elseif ($sortBy === 'price_desc') {
            $queryBuilder->orderBy('p.prix', 'DESC');
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
            'categories' => $categories,
            'selectedCategories' => $selectedCategories,
            
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



        /**
         * @Route("/produit/{id}", name="app_produit_detail")
         */
        public function showProductDetails($id, EntityManagerInterface $entityManager)
        {
            $produit = $entityManager->getRepository(Produit::class)->find($id);

            if (!$produit) {
                throw $this->createNotFoundException('The product does not exist');
            }

            return $this->render('produit/detail.html.twig', [
                'produit' => $produit,
            ]);
        }

        /**
             * @Route("/add_to_cart", name="add_to_cart")
             */
            public function addToCart(Request $request)
            {
                $productId = $request->request->get('getId');
                $quantity = $request->request->get('getQteStock');
                
                $entityManager = $this->getDoctrine()->getManager();
                $produit= $entityManager->getRepository(Produit::class)->find($productId);

                if (!$produit) {
                    throw $this->createNotFoundException(
                        'No product found for id '.$productId
                    );
                }
                
                $qteStock = $produit->getQtestock();
                
                if ($qteStock < $quantity) {
                    throw new \Exception("Not enough stock.");
                }
                
                $qteStock -= $quantity;
                $produit->setQtestock($qteStock);
                $entityManager->flush();
                
                return $this->json(['success' => true]);
        
        }

        /**
         * @Route("/increment-attribute/{id}", name="increment_attribute")
         */
        public function incrementAttribute(Produit $produit): JsonResponse
        {
            $entityManager = $this->getDoctrine()->getManager();
            $produit->setLikes($produit->getLikes() + 1);
            $entityManager->persist($produit);
            $entityManager->flush();

            return new JsonResponse(['like' => $produit->getLikes()]);
        }




}



