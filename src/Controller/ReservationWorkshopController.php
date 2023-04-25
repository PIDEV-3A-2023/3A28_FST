<?php

namespace App\Controller;

use App\Entity\ReservationWorkshop;
use App\Form\ReservationWorkshopType;
use App\Repository\ReservationWorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation/workshop')]
class ReservationWorkshopController extends AbstractController
{
    #[Route('/', name: 'app_reservation_workshop_index', methods: ['GET'])]
    public function index(ReservationWorkshopRepository $reservationWorkshopRepository): Response
    {
        return $this->render('reservation_workshop/index.html.twig', [
            'reservation_workshops' => $reservationWorkshopRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_workshop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationWorkshopRepository $reservationWorkshopRepository): Response
    {
        $reservationWorkshop = new ReservationWorkshop();
        $form = $this->createForm(ReservationWorkshopType::class, $reservationWorkshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationWorkshopRepository->save($reservationWorkshop, true);

            return $this->redirectToRoute('app_reservation_workshop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_workshop/new.html.twig', [
            'reservation_workshop' => $reservationWorkshop,
            'fm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_workshop_show', methods: ['GET'])]
    public function show(ReservationWorkshop $reservationWorkshop): Response
    {
        return $this->render('reservation_workshop/show.html.twig', [
            'reservation_workshop' => $reservationWorkshop,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_workshop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationWorkshop $reservationWorkshop, ReservationWorkshopRepository $reservationWorkshopRepository): Response
    {
        $form = $this->createForm(ReservationWorkshopType::class, $reservationWorkshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationWorkshopRepository->save($reservationWorkshop, true);

            return $this->redirectToRoute('app_reservation_workshop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_workshop/edit.html.twig', [
            'reservation_workshop' => $reservationWorkshop,
            'fm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_workshop_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationWorkshop $reservationWorkshop, ReservationWorkshopRepository $reservationWorkshopRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationWorkshop->getId(), $request->request->get('_token'))) {
            $reservationWorkshopRepository->remove($reservationWorkshop, true);
        }

        return $this->redirectToRoute('app_reservation_workshop_index', [], Response::HTTP_SEE_OTHER);
    }
}
