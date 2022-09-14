<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
                 $error = $authenticationUtils->getLastAuthenticationError();
                // last username entered by the user
                 $lastUsername = $authenticationUtils->getLastUsername();
                /* $formLogin = $this->createForm(LoginFormType::class);
                 if($formLogin->isSubmitted() && $formLogin->isValid()){
                     return $this->redirectToRoute('app_login');
                 }*/

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error'         => $error,
            //'formLogin' => $formLogin->createView()
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(AuthenticationUtils $authenticationUtils): Response
    {
    }
}
