<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use SessionIdInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;


class ProfileController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    
    #[Route('/profile', name: 'app_profile')]
    public function updateUser(HttpFoundationRequest $request){
        $userrr = $this->getUser();
        $user = new User();
    
        $form = $this->createForm(UserType::class, $user);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            $password = $request->request->get('password');
            $userrr->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $em = $this->getDoctrine()->getManager();
            $em->flush();
    
            return $this->redirectToRoute('app_profile');
        }
    
        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'usertab' => $user
        ]);
    }


}
