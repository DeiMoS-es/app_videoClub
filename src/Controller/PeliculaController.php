<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Pelicula;
use App\Entity\User;
use App\Form\PeliculaType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PeliculaController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'app_pelicula')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $peliculas = $this->em->getRepository(Pelicula::class)->findAllPeliculas();
        //$peliculas = $this->em->getRepository(Pelicula::class)->findAll();
        /*
        //dd($peliculas);
        $idUsuario = $peliculas[0]['user_id'];
        //dd($idUsuario->getId());
        //$user = $this->em->getRepository(User::class)->find($idUsuario);
        $user = $this->em->getRepository(User::class)->find($idUsuario);
        //$user = $this->em->getRepository(User::class)->findById(1);
        dd($user);
        */
        return $this->render('pelicula/index.html.twig', [
            'controller_name' => 'PeliculaController',
            'peliculas' => $peliculas
        ]);
    }

    #[Route('/insertar/pelicula', name: 'insertar_pelicula')]
    public function insert(Request $request,SluggerInterface $slugger):Response{
        $pelicula = new Pelicula();
        $form = $this->createForm(PeliculaType::class, $pelicula);//Genero el formulario, y lo relaciono con la entidad
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $file = $form->get('foto')->getData();
            $url = str_replace(" ", "-", $form->get('titulo')->getData());
            if($file){
                $newFilename = uniqid().'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Problema con el archivo');
                }
                $pelicula->setFoto($newFilename);
            }
            //TODO cambiar por una url correcta a un trailer
            $pelicula->setUrl($url);
            $user = $this->em->getRepository(User::class)->find(1);
            $pelicula->setUser($user);
            $this->em->persist($pelicula);
            $this->em->flush();
            return $this->redirectToRoute('app_pelicula');
        }

        return $this->render('pelicula/pelicula-insertar.html.twig', [
            'controller_name' => 'PeliculaController',
            'form' => $form->createView(),
        ]);
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

    #[Route('/details/pelicula/{id}', methods:['GET'] ,name: 'details_pelicula')]
    public function details($id):Response{
        $pelicula = $this->em->getRepository(Pelicula::class)->find($id);

        return $this->render('pelicula/pelicula-details.html.twig', ['pelicula' => $pelicula]);
    }
}
