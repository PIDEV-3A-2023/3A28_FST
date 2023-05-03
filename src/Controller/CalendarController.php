<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(EvenementRepository $ev): Response
    {
        $events=$ev ->findAll();
        $rdvs = [];
        foreach($events as $event){
            $rdvs[]=[
                'id' =>$event->getId(),
                'start' => $event->getDateDebut()->format('Y-m-d\TH:i:s'),
                'end' => $event->getDateFin()->format('Y-m-d\TH:i:s'),
                'title' => $event->getTitre(),
                'description' => $event->getDescription(),

            ];

        }
        $data = json_encode($rdvs);
        return $this->render('calendar/index.html.twig',compact('data'));
    }
}
