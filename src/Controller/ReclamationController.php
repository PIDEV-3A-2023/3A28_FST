<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Config\KnpPaginatorConfig;

class ReclamationController extends AbstractController
{
    #[Route('/gestion/reclamation', name: 'listereclam')]
    public function reclamations(Request $request,EntityManagerInterface $entityManager , PaginatorInterface $paginator): Response
    {
        
     

        $query = $entityManager->getRepository(Reclamation::class)->createQueryBuilder('r')->getQuery();

        
        $reclamations = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('reclamation/listeReclam.html.twig', [
            "reclamations" => $reclamations
        ]);
    }

    // client seulement 

    #[Route('/reclamation/add', name: 'addReclam')]
    public function addReclamation(Request $request, Security $security, CsrfTokenManagerInterface $csrfTokenManager)
    {  
        $reclamation = new Reclamation();

        $reclamation->setUserid(intval($security->getUser()->getId()));
        
        $form = $this->createForm(ReclamType::class, $reclamation);
        $form->handleRequest($request);
    
        $token = new CsrfToken('reclam', $request->request->get('_token'));

        if ($form->isSubmitted() && $form->isValid()) {
            dump("ok");
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->flush();
            $em->refresh($reclamation);
            dump($reclamation->getId());
            return $this->redirectToRoute('app_profile');
        }
    
        return $this->render('reclamation/addReclam.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    //admin

    #[Route('/gestion/reclamation/delete/{id}', name: 'deleteReclam')]
    public function deleteReclamation($id)
    {
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);;
        $em = $this->getDoctrine()->getManager();
        $em->remove($reclamation);
        $em->flush();
        return $this->redirectToRoute("listereclam");
    }

    #[Route('/gestion/reclamation/update/{id}', name: 'updateReclam')]
public function updateReclamation(Request $request,$id)
{   $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
    $form = $this->createForm(ReclamType::class, $reclamation);
    $form->add('modifier',SubmitType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('listereclam');
    }
    return $this->render("reclamation/updateReclam.html.twig",array('form'=>$form->createView()));
}
    
    
    


}
