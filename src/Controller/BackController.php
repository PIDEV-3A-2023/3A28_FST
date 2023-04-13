<?php

namespace App\Controller;

use App\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    #[Route('/back', name: 'app_back')]
    public function index(): Response
    {
        $data = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        return $this->render('back/index.html.twig', [
            'list' => $data
        ]);
    }
}
