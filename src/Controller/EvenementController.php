<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\ResevationRepository;
use App\Services\QrcodeService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Factory\QrCodeFactoryInterface;
use Endroid\QrCodeBundle\Controller\QrCodeController;
use Endroid\QrCode\Writer\DataUriWriter;
use Endroid\QrCode\Writer\PngWriter;
use App\Entity\Resevation;
use Symfony\Component\Security\Core\Security;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        $data = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        return $this->render('evenement/index.html.twig', [
            'list' => $data
        ]);
    }
    #[Route('/create', name: 'create')]
    public function create(Request $request, SluggerInterface $slugger)
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $evenement->setImage($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            $this->addFlash('notice', 'Submitted');
        }
        return $this->render('evenement/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/update/{id}', name: 'update')]
    public function update(Request $request, $id)
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            $this->addFlash('notice', 'Submitted');
        }
        return $this->render('evenement/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id)
    {
        $data = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();
        $this->addFlash('notice', ' udelete Submitted');
        return $this->redirectToRoute('app_back');
    }
    #[Route('/details/{id}', name: 'details')]
    public function details(Request $request, $id)
    {
        $event = $this->getDoctrine()->getRepository(Evenement::class)->find($id);

        return $this->render('evenement/details.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/evenements/search', name: 'evenements_search')]

    public function search(Request $request, EntityManagerInterface $entityManager)
    {
        $term = $request->query->get('search');

        $repository = $entityManager->getRepository(Evenement::class);
        $evenements = $repository->createQueryBuilder('e')
            ->where('e.titre LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->getResult();

        $results = [];
        foreach ($evenements as $evenement) {
            $results[] = [
                'id' => $evenement->getId(),
                'text' => $evenement->getTitre(),
            ];
        }

        return $this->render('evenement/search_results.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/generate_qr_code/{id}', name: 'qrCode')]

    public function generateQrCodeAction(QrcodeService $qrcodeService, Evenement $evenement, ResevationRepository $repo_r,Security $security)
    {


        //Test s'il y a des place dans l'evenement 
        // if ($event->getNbPlace() > 0) {
        $qrCode = $qrcodeService->qrcode($evenement);

        $entityManager = $this->getDoctrine()->getManager();
        $reservation = new Resevation();
        $nbPlaces = $evenement->getNbPlace();
        $id = $evenement->getId();
        $reservations = $repo_r->findByEvent($id);

    
            //Remplir la table réservation (affecter le user à cette evenement)
            $reservation->setEventId($evenement);
            $reservation->setUserId(intval($security->getUser()->getId()));

        if ($nbPlaces > 0) {
            $entityManager->persist($reservation);
            $entityManager->flush();


            //Diminuer le nb de place de cette evenement

            $evenement->setNbPlace($nbPlaces - 1);
            $entityManager->flush();


        }
        else {
            $nbPlaces = 0;

        }
        //Retourner msg d'erreur ( dsl pas assez de places)
        return $this->render('evenement/details.html.twig', [
            'event' => $evenement,
            'qrCode' => $qrCode,
            
        ]);
    }
    #[Route('/event/{id}/rate', name: 'rate_event')]
    public function rateEvent(Request $request, Evenement $evenement): JsonResponse
    {
        $rating = $request->request->getInt('rating');

        $entityManager = $this->getDoctrine()->getManager();
        $ratingNumber = $evenement->getRatingNumber();

        if ($ratingNumber === 0) {
            $newRating = $rating;
        } else {
            $currentRating = $evenement->getRating();
            $newRating = ($currentRating * $ratingNumber + $rating) / ($ratingNumber + 1);
        }

        $evenement->setRating($newRating);
        $evenement->setRatingNumber($ratingNumber + 1);

        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
    #[Route('/annuler/{id}', name: 'annuler')]
    public function annuler(Evenement $evenement, ResevationRepository $repo_r, $id, Security $security)
    {
        $event = $this->getDoctrine()->getRepository(Evenement::class)->find($id);

        $userId = $security->getUser()->getId();
       
        
        // id user et id ev dans tables reservation
        // $user = $this->getDoctrine()->getRepository(User::class)->find(1);

        $reservation = $this->getDoctrine()->getRepository(Resevation::class)->findBy([
            'event_Id' => $event,
            'user_Id' => $userId ,
        ]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($reservation[0]);
        $em->flush();
        $nbPlaces = $event->getNbPlace();
        $event->setNbPlace($nbPlaces + 1);
        $em->flush();


        return $this->render('evenement/details.html.twig', [
            'event' => $event,
        ]);
    }
}
