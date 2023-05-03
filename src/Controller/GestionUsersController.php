<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class GestionUsersController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    
    #[Route('/gestion/users', name: 'admin')]
    public function admin()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        
        return $this->render('gestion_users/index.html.twig', array("users" => $users));
    }
    #[Route('/gestion/users/{search}', name: 'search')]
    public function search( Request $request,$search)
    {
        
            
          
                $users = array($this->getDoctrine()->getRepository(User::class)->findOneBy(array("username"=>$search)));
            
               
            
            dump($users);
        
        return $this->render('gestion_users/index.html.twig', array("users" => $users));
    }
    #[Route('/gestion/users/delete/{id}', name: 'deleteUser')]
    public function deleteUser($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("admin");
    }

    #[Route('/gestion/users/update/{id}', name: 'updateUser')]
    public function updateUser(Request $request,$id)
    {   $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('admin');
        }
        return $this->render("gestion_users/update.html.twig",array('form'=>$form->createView()));
    }



}
