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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


#[Route('/workshop')]
class WorkshopController extends AbstractController
{




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
            $queryBuilder->where('w.nom_artiste LIKE :searchTerm OR w.description LIKE :searchTerm OR w.prix LIKE :searchTerm')
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
            'searchTerm' => $searchTerm, // Pass the search term to the view
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


    #[Route('details/{id}', name: 'details')]
    public function details(Request $request, $id){
        $workshop = $this->getDoctrine()->getRepository(Workshop::class)->find($id);

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
                return $this->redirectToRoute('app_produit');
            }

            return $this->redirectToRoute('app_produit', ['query' => $query]);
        }

}
