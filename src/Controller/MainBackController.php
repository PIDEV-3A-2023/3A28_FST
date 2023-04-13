<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainBackController extends AbstractController
{
    #[Route('/main/back', name: 'app_main_back')]
    public function index(): Response
    {
        return $this->render('main_back/index.html.twig', [
            'controller_name' => 'MainBackController',
        ]);
    }
}
