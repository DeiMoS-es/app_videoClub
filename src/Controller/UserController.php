<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UpdateUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\HasPasswordService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use function Doctrine\Common\Cache\Psr6\get;

class UserController extends AbstractController
{
    private $em;
    private $hasPasswordService;
    private $userRepository;

    /**
     * UserController constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em, HasPasswordService $hasPasswordService, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->hasPasswordService = $hasPasswordService;
        $this->userRepository = $userRepository;
    }

    #[Route('/registro', name: 'registroUsuario')]
    public function registroUsuario(Request $request): Response
    {
        $user = new User();
        $registration_form = $this->createForm(RegistrationFormType::class, $user);
        $registration_form->handleRequest($request);
        if ($registration_form->isSubmitted() && $registration_form->isValid()){
            $plaintextPassword = $registration_form->get('plainPassword')->getData();
            $newPassword = $this->hasPasswordService->encriptarPassword($plaintextPassword, $user);
            $foto = $registration_form->get('foto')->getData();
            if ($foto){
                $newFilename = uniqid().'.'.$foto->guessExtension();
                try {
                    $foto->move(
                        $this->getParameter('foto_usuarios'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Problema con el archivo');
                }
                $user->setPhoto($newFilename);
            }else{
                $user->setPhoto('default.jpg');
            }
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

    #[Route('/update/usuario/{id}', name: 'update_usuario')]
    public function updateUsuario($id, Request $request): Response
    {
         $user = $this->userRepository->findOneById($id);
         $form = $this->createForm(UpdateUserType::class, $user);
         $form->handleRequest($request);
         $foto = $form->get('photo')->getData();
         if ($form->isSubmitted() && $form->isValid()){
             if ($foto){
                 if($user->getPhoto() !== null){
                     if (file_exists($this->getParameter('foto_usuarios').$user->getPhoto())){
                         $nuevoNombre = uniqid().'.'.$foto->guessExtension();
                         try {
                             $foto->move(
                                 $this->getParameter('foto_usuarios'),
                                 $nuevoNombre
                             );
                             //Necesitamos eliminar la foto anterior si se ha actualizado la foto
                             $pathFoto = '../public/uploads/files/imagesPerfil/';
                             $fotoVieja = $user->getPhoto();
                             if($fotoVieja != 'default.jpg'){
                                 unlink($pathFoto.$fotoVieja);
                             }
                         } catch (FileException $e) {
                             throw new \Exception('Problema con el archivo');
                         }
                         $user->setPhoto($nuevoNombre);
                         $this->em->flush();
                         return $this->redirectToRoute('app_pelicula');
                     }
                 }
             }else{
                 $user->setEmail($form->get('email')->getData());
                 $user->setRoles($form->get('roles')->getData());
                 //TODO actualizar password
                 $password = $form->get('password')->getData();
                 $newPas = $this->hasPasswordService->encriptarPassword($password, $user);
                 $user->setPassword($form->get('$newPas')->getData());
                 $user->setDescription($form->get('description')->getData());
                 $user->setUpdateOn(new \DateTime());
                 $this->em->flush();
                 return $this->redirectToRoute('app_pelicula');
             }
         }
        return $this->render('usuarios/usuario-editar.html.twig',[
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
