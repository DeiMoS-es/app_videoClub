<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Service\HasPasswordService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $em;
    private $hasPasswordService;

    /**
     * UserController constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em, HasPasswordService $hasPasswordService)
    {
        $this->em = $em;
        $this->hasPasswordService = $hasPasswordService;
    }

    #[Route('/registro', name: 'registroUsuario')]
    public function registroUsuario(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $registration_form = $this->createForm(RegistrationFormType::class, $user);
        $registration_form->handleRequest($request);
        if ($registration_form->isSubmitted() && $registration_form->isValid()){
            $plaintextPassword = $registration_form->get('plainPassword')->getData();
            $newPassword = $this->hasPasswordService->encriptarPassword($plaintextPassword, $user);
            $user->setPassword($newPassword);
            $user->setRoles(['ROLE_USER']);
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $registration_form->createView(),
        ]);
    }
}
