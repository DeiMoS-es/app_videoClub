<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Pelicula;
use App\Entity\User;
use App\Form\PeliculaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PeliculaController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'app_pelicula')]
    public function index(Request $request): Response
    {
        $pelicula = new Pelicula();
        $form = $this->createForm(PeliculaType::class, $pelicula);//Genero el formulario, y lo relaciono con la entidad
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user = $this->em->getRepository(User::class)->find(1);
            $pelicula->setUser($user);
            $this->em->persist($pelicula);
            $this->em->flush();
            return $this->redirectToRoute('app_pelicula');
        }
        return $this->render('pelicula/index.html.twig', [
            'controller_name' => 'PeliculaController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/insertar/pelicula', name: 'insertar_pelicula')]
    public function insert(){
        $pelicula = new Pelicula('Superman2', 'Accion', 'This is the description of Superman', 'foto4.jpg', 'www.google.com' );
        $usuario = $this->em->getRepository(User::class)->find(1);
        $pelicula->setUser($usuario);
        $this->em->persist($pelicula);
        $this->em->flush();

        return new JsonResponse(['succes' => true]);
    }

    #[Route('/update/pelicula', name: 'update_pelicula')]
    public function update(){
        $pelicula = $this->em->getRepository(Pelicula::class)->find(4);
        $pelicula->setTipo('Humor');
        $this->em->flush();

        return new JsonResponse(['succes' => true]);
    }

    #[Route('/remove/pelicula', name: 'remove_pelicula')]
    public function remove(){
        $pelicula = $this->em->getRepository(Pelicula::class)->find(5);
        $this->em->remove($pelicula);
        $this->em->flush();

        return new JsonResponse(['succes' => true]);
    }
}
