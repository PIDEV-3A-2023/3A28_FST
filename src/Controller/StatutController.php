<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\StatutRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Statut;
use App\Form\StatutType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;

use Symfony\Component\Routing\Annotation\Route;

class StatutController extends AbstractController
{
    #[Route('/statut/fetch', name: 'showstatut')]
    public function index(StatutRepository $repo, Request $request): Response
    {
        $statuts = $repo->findAll();
        $last = $repo->latest_posts();

        return $this->render('statut/index.html.twig', [
            'stat' => $statuts,
            'last' => $last,
        ]);
    }

    #[Route('/statut/add', name: 'addstatut')]
    public function addstatut(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $em = $doctrine->getManager();
        $statut = new Statut();
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('statut_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $statut->setImage($newFilename);
            }
            $statut->setNbrLike(0);
            $statut->setCreated(new \DateTime("now"));
            $em->persist($statut);
            $em->flush();
            return $this->redirectToRoute('showstatut');
        }
        return $this->renderForm('statut/ajoutstatut.html.twig', [
            'f' => $form,
            
        ]);
    }

    #[Route('/statut/remove/{id}', name: 'removestatut')]
    public function remove(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $statut = $doctrine->getRepository(Statut::class)->find($id);

        $em->remove($statut);
        $em->flush();
        return $this->redirectToRoute('showstatut');
    }

    #[Route('/statut/updatestatut/{id}', name: 'updatestatut')]
    public function update(ManagerRegistry $doctrine, Request $request, $id, StatutRepository $repo): Response
    {
        $em = $doctrine->getManager();
        $statut = $doctrine->getRepository(Statut::class)->find($id);
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $statut->setUpdated(new \DateTime("now"));
            return $this->redirectToRoute('showstatut');
        }
        return $this->renderForm('statut/modifierstatut.html.twig', [
            'f' => $form,
        ]);
    }

    #[Route('/searchajax', name: 'rechajax')]

    public function searchajax(Request $request, StatutRepository $rep)
    {
        $search = $request->get('searchValue');
        $statuts = $rep->findByTitreOrContenu($search);
        $last = $rep->latest_posts();


        return $this->render('statut/ajax.html.twig', [
            'stat' => $statuts,
            'last' => $last,


        ]);
    }

    #[Route('/statut/filtre/{type}', name: 'filtretype')]
    public function filtretype(StatutRepository $repo, $type): Response
    {
        $statut = $repo->findBytype($type);
        $last = $repo->latest_posts();


        return $this->render('statut/index.html.twig', [
            'stat' => $statut,
            'last' => $last,
        ]);
    }
    #[Route('/statut/likes/{id}', name: 'likes')]
    public function likes(StatutRepository $rp, $id): Response
    {
        $Statut = $rp->find($id);
        $Statut->setNbrLike($Statut->getNbrLike() + 1);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->flush();

        return $this->redirectToRoute('showstatut', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/statut/dislikes/{id}', name: 'dislikes')]
    public function dislikes(StatutRepository $rp, $id): Response
    {
        $statut = $rp->find($id);
        $statut->setNbrLike($statut->getNbrLike() - 1);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->flush();

        return $this->redirectToRoute('showstatut', [], Response::HTTP_SEE_OTHER);
    }
}
