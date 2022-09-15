<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Pelicula;
use App\Entity\User;
use App\Form\PeliculaType;
use App\Repository\PeliculaRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
    private $peliculaRepositorio;
    public function __construct(EntityManagerInterface $em, PeliculaRepository $peliculaRepositorio)
    {
        $this->em = $em;
        $this->peliculaRepositorio = $peliculaRepositorio;
    }

    #[Route('/', name: 'app_pelicula')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $peliculas = $this->em->getRepository(Pelicula::class)->findAllPeliculasAndUser();

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
            }else{//Si no se introduce una imagen, se pone una por defecto
                $pelicula->setFoto('default.jpg');
            }
            $pelicula->setUrl($url);
            $actores = $form->get('actores')->getData();//Recupero un Array con los actores introducidos
            foreach ($actores as $actor){//se añaden los actores uno a uno
                $pelicula->addActore($actor);
            }
            $pelicula->setFechaAlta(new \DateTime());
            $user = $this->em->getRepository(User::class)->find(6);
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

    #[Route('/update/pelicula/{id}', name: 'update_pelicula')]
    public function update($id, Request $request):Response{
        $pelicula = $this->peliculaRepositorio->find($id);
        $form = $this->createForm(PeliculaType::class, $pelicula);
        $form->handleRequest($request);

        $foto = $form->get('foto')->getData();
        //$foto = $pelicula->getFoto();

        if ($form->isSubmitted() && $form->isValid()){
           // dd($foto);
            if ($foto){
                if ($pelicula->getFoto() !== null){
                    if (file_exists($this->getParameter('files_directory').'/'.$pelicula->getFoto())){
                        $nuevoNombre = uniqid().'.'.$foto->guessExtension();
                        try {
                            $foto->move(
                                $this->getParameter('files_directory'),
                                $nuevoNombre
                            );
                            //Necesitamos eliminar la foto anterior si se ha actualizado la foto
                            $pathFoto = '../public/uploads/files/';
                            $fotoVieja = $pelicula->getFoto();
                            if($fotoVieja != 'default.jpg'){
                                unlink($pathFoto.$fotoVieja);
                            }
                        } catch (FileException $e) {
                            throw new \Exception('Problema con el archivo');
                        }
                        $pelicula->setFoto($nuevoNombre);
                        $this->em->flush();
                        return $this->redirectToRoute('app_pelicula');
                    }
                }
            }else{
               $pelicula->setTitulo($form->get('titulo')->getData());
               $pelicula->setTipo($form->get('tipo')->getData());
               $pelicula->setDescripcion($form->get('descripcion')->getData());
               $this->em->flush();
               return $this->redirectToRoute('app_pelicula');
            }
        }
        return $this->render('pelicula/pelicula-editar.html.twig',[
            'pelicula' => $pelicula,
            'form' => $form->createView()
        ]);
    }

    #[Route('/remove/pelicula/{id}', name: 'remove_pelicula')]
    public function remove($id){
        $pelicula = $this->peliculaRepositorio->find($id);
        $this->em->remove($pelicula);
        $this->em->flush();

        return $this->redirectToRoute('app_pelicula');
    }

    #[Route('/details/pelicula/{id}', methods:['GET'] ,name: 'details_pelicula')]
    public function details($id):Response{
        $pelicula = $this->em->getRepository(Pelicula::class)->find($id);
        $newArrayActores = $pelicula->getActores();
        return $this->render('pelicula/pelicula-details.html.twig', ['pelicula' => $pelicula, 'actores'=>$newArrayActores]);
    }
}
