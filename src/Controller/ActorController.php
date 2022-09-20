<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Form\ActorFormType;
use App\Repository\ActorRepository;
use App\Service\ActorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActorController extends AbstractController
{
    private $actorService;
    private $em;

    public function __construct(EntityManagerInterface $em, ActorService $actorService)
    {
        $this->em = $em;
        $this->actorService = $actorService;
    }

    #[Route('/actor', methods: ['GET'], name: 'app_actor')]
    public function index(): Response
    {
        $actores = $this->actorService->buscarTodos();
        return $this->render('actor/index.html.twig', [
            'controller_name' => 'ActorController',
            'actores' => $actores
        ]);
    }

    #[Route('/insertar/actor', methods: ['GET','POST'],name: 'insertar_actor')]
    public function insertar(Request $request): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorFormType::class, $actor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $fotoActor = $form->get('fotoActor')->getData();
            $this->actorService->insertarFoto($actor, $fotoActor);
            $peliculas = $form->get('peliculas')->getData();
            foreach ($peliculas as $pelicula){
                $actor->addPelicula($pelicula);
            }
            $this->actorService->saveActor($actor);
            return $this->redirectToRoute('app_actor');
        }
        return $this->render('actor/actor-insertar.html.twig', [
            'controller_name' => 'ActorController',
            'formActor' => $form->createView()
        ]);
    }

    #[Route('/update/actor/{id}', name: 'update_actor')]
    public function update(Request $request, $id):Response{
        $actor = $this->actorService->buscarActorId($id);
        $form = $this->createForm(ActorFormType::class, $actor);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $fotoActor = $form->get('fotoActor')->getData();
            $this->actorService->editarFoto($actor, $fotoActor);
            $peliculas = $form->get('peliculas')->getData();
            foreach ($peliculas as $pelicula){
                $actor->addPelicula($pelicula);
            }
            $this->actorService->saveActor($actor);
            return $this->redirectToRoute('app_actor');
        }
        return $this->render('actor/actor-editar.html.twig',[
            'formEditActor' => $form->createView()
        ]);
    }

    #[Route('/remove/actor/{id}', name: 'remove_actor')]
    public function remove(Request $request, $id):Response{
        $actor = $this->actorService->buscarActorId($id);
        $this->em->remove($actor);
        $this->em->flush();
        return $this->redirectToRoute('app_actor');
    }

    #[Route('/details/actor/{id}', name: 'details_actor')]
    public function details($id):Response{
        $actor = $this->actorService->buscarActorId($id);
        $newArrayPeliculas = $actor->getPeliculas();
        return $this->render('actor/actor-details.html.twig', [
            'actor'=>$actor,
            'peliculas'=>$newArrayPeliculas
        ]);
    }
}
