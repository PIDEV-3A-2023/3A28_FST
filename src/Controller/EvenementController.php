<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Evenement;
use App\Form\EvenementType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

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
        return $this->redirectToRoute('app_evenement');
    }
    #[Route('/details/{id}', name: 'details')]
    public function details(Request $request, $id){
        $event = $this->getDoctrine()->getRepository(Evenement::class)->find($id);

        return $this->render('evenement/details.html.twig', [
            'event' => $event,
        ]);
    }
}
