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

    #[Route('/actor', name: 'app_actor')]
    public function index(): Response
    {
        $actores = $this->actorService->buscarTodos();
        return $this->render('actor/index.html.twig', [
            'controller_name' => 'ActorController',
            'actores' => $actores
        ]);
    }

    #[Route('insertar/actor', name: 'insertar_actor')]
    public function insertar(Request $request): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorFormType::class, $actor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $fotoActor = $form->get('fotoActor')->getData();
            $this->actorService->insertarFoto($actor, $fotoActor);
            $this->actorService->saveActor($actor);
            return $this->redirectToRoute('app_actor');
        }
        return $this->render('actor/actor-insertar.html.twig', [
            'controller_name' => 'ActorController',
            'formActor' => $form->createView()
        ]);
    }
}
