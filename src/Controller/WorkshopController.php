<?php

namespace App\Controller;

use App\Entity\ReservationWorkshop;
use App\Entity\Workshop;
use App\Form\WorkshopType;
use App\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;



#[Route('/workshop')]
class WorkshopController extends AbstractController
{



    #[Route('/stat', name: 'stat', methods: ['GET'])]
    public function stats(Request $request): Response
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Workshop::class);


        $categorie = $request->query->get('categorie');
    
        $categoryData = ['count' => $entityManager->getRepository(Workshop::class)->countBy('categorie', $categorie)];

        $workshopData = [$repository->countReservationsByWorkshop()];

    
        // Configuration du graphique en camembert pour les catégories
        $categoryConfig = [
            'type' => 'pie',
            'data' => [
                'datasets' => [
                    [
                        'data' => array_values($categoryData),
                        'backgroundColor' => [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        'borderColor' => [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                        ],
                        'borderWidth' => 1,
                    ],
                ],
                'labels' => array_keys($categoryData),
            ],
            'options' => [
                'responsive' => true,
                'title' => [
                    'display' => true,
                    'text' => 'Répartition des réservations par catégorie',
                ],
            ],
        ];
    
       // Configuration du graphique en aires pour les réservations par atelier
$workshopConfig = [
    'type' => 'line',
    'data' => [
        'datasets' => array_map(function ($data) {
            if (isset($data['workshop'])) {
                return [
                    'label' => $data['workshop']->getName(),
                    'data' => array_values($data['count']),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'fill' => false,
                ];
            }
        }, $workshopData),
    ],
    'options' => [
        'responsive' => true,
        'title' => [
            'display' => true,
            'text' => 'Réservations par atelier',
        ],
        'scales' => [
            'xAxes' => [
                [
                    'type' => 'category',
                    'labels' => array_map(function ($data) {
                        return $data['workshop']->getName();
                    }, $workshopData),
                ],
            ],
            'yAxes' => [
                [
                    'ticks' => [
                        'beginAtZero' => true,
                    ],
                ],
            ],
        ],
    ],
];
        return $this->render('stat.html.twig', [
            
            'workshopConfig' => $workshopConfig,
            'categoryConfig' => $categoryConfig,
        ]);
    }
    
    

    #[Route('/client', name: 'app_workshop1')]
    public function indexxx(EntityManagerInterface $entityManager, Request $request,PaginatorInterface $paginator){
        $queryBuilder = $entityManager->getRepository(Workshop::class)->createQueryBuilder('w');
    
        $searchTerm = $request->query->get('query');
        $category = $request->query->get('categorie');
        $niveau = $request->query->get('niveau');
        
      
        if ($category) {
            $queryBuilder->andWhere('w.categorie = :category')
                ->setParameter('category', $category);
        }

        if ($niveau) {
            $queryBuilder->andWhere('w.niveau = :niveau')
                ->setParameter('niveau', $niveau);
        }
        
    
        if ($searchTerm) {
            $queryBuilder->where(' w.categorie LIKE :searchTerm ')
                ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }
        
        $query = $queryBuilder->getQuery();
       
        $wps = $paginator->paginate(
            $query, // Query results to paginate
            $request->query->getInt('page', 1), // Current page number
          6 // Number of items per page
        );
        $categories = $entityManager->getRepository(Workshop::class)->findAllCategories();
        $niveaux = [
            'Facile' => 'Facile',
            'Intermédiaire' => 'Intermédiaire',
            'Avancé' => 'Avancé',
        ];

        return $this->render('indexClient.html.twig', [
            'controller_name' => 'WorkshopController',
            'wps' => $wps,
      
            'categories' => $categories,
            'selectedCategory' => $category,
            'niveaux' => $niveaux,
            'selectedNiveau' => $niveau,
           
        ]);
    }




    #[Route('/', name: 'app_workshop_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,WorkshopRepository $workshopRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $queryBuilder = $entityManager->getRepository(Workshop::class)->createQueryBuilder('r');
        
       
        


        $query = $queryBuilder->getQuery();
        $rs = $paginator->paginate(
            $query, // Query results to paginate
            $request->query->getInt('page', 1), // Current page number
           6 // Number of items per page
        );
        return $this->render('workshop/index.html.twig', [
            'controller_name' => 'WorkshopController',
            'rs' => $rs,
           
        ]);
    }





    #[Route('/admin', name: 'app_workshop', methods: ['GET'])]
    public function indexAdmin(WorkshopRepository $workshopRepository): Response
    {
        return $this->render('workshopAdmin/index.html.twig', [
            'workshops' => $workshopRepository->findAll(),
        ]);
    }
    #[Route('/newA', name: 'addwA', methods: ['GET', 'POST'])]
    public function newAdmin(Request $request, WorkshopRepository $workshopRepository,SluggerInterface $slugger): Response
    {
        $workshop = new Workshop();
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);
        $workshop->setUserid(1);

        if ($form->isSubmitted() && $form->isValid()) {


             //imagedocum
             $image = $form->get('image')->getData();
            
            

             // this condition is needed because the 'brochure' field is not required
             // so the PDF file must be processed only when a file is uploaded
             if ($image) {
                 $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                 // this is needed to safely include the file name as part of the URL
                 $safeFilename = $slugger->slug($originalFilename);
                 $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
 
                 // Move the file to the directory where brochures are stored
                 try {
                     $image->move(
                         $this->getParameter('image_directory'),
                         $newFilename
                     );
                 } catch (FileException $e) {
                     // ... handle exception if something happens during file upload
                 }
 
                 // updates the 'brochureFilename' property to store the PDF file name
                 // instead of its contents
                 $workshop->setImage($newFilename);
             }
             
            $workshopRepository->save($workshop, true);

            return $this->redirectToRoute('app_workshop', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('workshopAdmin/new.html.twig', [
            'workshop' => $workshop,
            'f' => $form,
        ]);
    }
    #[Route('/new', name: 'app_workshop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WorkshopRepository $workshopRepository,SluggerInterface $slugger): Response
    {
        $workshop = new Workshop();
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);
        $workshop->setUserid(1);
        if ($form->isSubmitted() && $form->isValid()) {
            //imagedocum
            $image = $form->get('image')->getData();
            
            

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $workshop->setImage($newFilename);
            }
            $workshopRepository->save($workshop, true);
           

            return $this->redirectToRoute('app_workshop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('workshop/new.html.twig', [
            'workshop' => $workshop,
            'f' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_workshop_show', methods: ['GET'])]
    public function show(Workshop $workshop): Response
    {
        return $this->render('workshop/show.html.twig', [
            'workshop' => $workshop,
        ]);
    }
    
    #[Route('/{id}/editA', name: 'updateWorkshopp', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, Workshop $workshop, WorkshopRepository $workshopRepository): Response
    {
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workshopRepository->save($workshop, true);

            return $this->redirectToRoute('app_workshop', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('workshopAdmin/edit.html.twig', [
            'workshop' => $workshop,
            'f' => $form,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_workshop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Workshop $workshop, WorkshopRepository $workshopRepository): Response
    {
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workshopRepository->save($workshop, true);

            return $this->redirectToRoute('app_workshop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('workshop/edit.html.twig', [
            'workshop' => $workshop,
            'f' => $form,
        ]);
    }
    

    #[Route("delete/{id}", name:'app_workshop_delete')]
    public function deletee($id, ManagerRegistry $doctrine)
    {$s = $doctrine
        ->getRepository(Workshop::class)
        ->find($id);
        $em = $doctrine->getManager();
        $em->remove($s);
        $em->flush() ;
        return $this->redirectToRoute('app_workshop_index');
    }

    #[Route("deletee/{id}", name:'deleteWorkshopAdmin')]
    public function delete($id, ManagerRegistry $doctrine)
    {$s = $doctrine
        ->getRepository(Workshop::class)
        ->find($id);
        $em = $doctrine->getManager();
        $em->remove($s);
        $em->flush() ;
        return $this->redirectToRoute('app_workshop');
    }


    #[Route('detailss/{id}', name: 'detailss')]
    public function details(Request $request, $id , WorkshopRepository $workshopRepository){
        $workshop = $this->getDoctrine()->getRepository(Workshop::class)->find($id);
        $workshopRepository->sms();
        $this->addFlash('danger', 'reponse envoyée avec succées');

        return $this->render('workshopDetails.html.twig', [
            'wss' => $workshop,
        ]);
    }

  


    
      /**
         * @Route("/search", name="search")
         */
        public function search(Request $request): Response
        {
            $query = $request->query->get('query');

            if (!$query) {
                return $this->redirectToRoute('app_workshop1');
            }

            return $this->redirectToRoute('app_workshop1', ['query' => $query]);
        }
}
