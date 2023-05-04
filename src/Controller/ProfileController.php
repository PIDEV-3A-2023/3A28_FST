<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Security\Csrf\CsrfToken;

class ProfileController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Security('is_granted("ROLE_USER")')]
    #[Route('/profile', name: 'app_profile')]
    public function updateUser(Request $request)
    {
        $userrr = $this->getUser();
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

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
