<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

class CreateUser
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request)
    {
        $user = new User();
    }

    public function moverFotoPerfil(User $user, $foto){
        if ($user->getPhoto() !== null){
            if(file_exists($this->getParameter('foto_usuarios').$user->getPhoto())){
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
                //$user->setPhoto($nuevoNombre);
                //$this->em->flush();
                return $nuevoNombre;
            }
        }

    }
}