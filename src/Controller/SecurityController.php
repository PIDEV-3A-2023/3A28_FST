<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\ForgotType;
use App\Repository\UserRepository;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    


    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->redirectToRoute("app_login");
    }

    #[Route(path: '/forgot', name: 'app_forgot')]
    public function forgot(Request $request,UserRepository $userRepository ,MailerInterface $mailer,TokenGeneratorInterface $tokenGenerator)
    {
        $form = $this->createForm(ForgotType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $donnees = $form->getData();
            $user= $userRepository->findOneBy(['email'=>$donnees]);
            if(!$user){
                $this->addFlash('danger','cette adresse n\'existe pas ');
                return $this->redirectToRoute("app_login");
            }
            $token = $tokenGenerator->generateToken();
            try{
                $user->setResetToken($token);
                $entityManger = $this->getDoctrine()->getManager();
                $entityManger->persist($user);
                $entityManger->flush();
            }catch(\Exception $ex){
                $this->addFlash('warning','une erreur est survenue :'.$ex->getMessage());
                return $this->redirectToRoute("app_login");
            }
            $url = $this -> generateUrl('app_reset',array('token'=>$token),UrlGeneratorInterface::ABSOLUTE_PATH);

            $email = (new Email())
            ->from('artstation2223@gmail.com')
            ->to($user->getEmail())
            ->subject('Mot de password oublié')
            ->text(' une demande de réinitialisation de mot de passe a été effectuée. Veuillez cliquer sur le lien suivant : https://127.0.0.1:8000'.$url);


        $mailer->send($email);
        }
        return $this->render("security/forgot.html.twig",['form'=>$form->createView()]);
        }

        #[Route(path: '/reset/{token}', name: 'app_reset')]
        public function resetpassword(Request $request,string $token, UserPasswordEncoderInterface  $passwordEncoder)
        {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token'=>$token]);
    
            if($user == null ) {
                $this->addFlash('danger','TOKEN INCONNU');
                return $this->redirectToRoute("app_login");
    
            }
            
            if($request->isMethod('POST')) {
                $user->setResetToken(null);
    
                $user->setPassword($passwordEncoder->encodePassword($user,$request->request->get('password')));
                $entityManger = $this->getDoctrine()->getManager();
                $entityManger->persist($user);
                $entityManger->flush();
    
                $this->addFlash('message','Mot de passe mis à jour :');
                return $this->redirectToRoute("app_login");
    
            }
            else {
                return $this->render("security/reset.html.twig",['token'=>$token]);
    
            }
        }
    
}
