<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PeliculaController extends AbstractController
{
    #[Route('/pelicula', name: 'app_pelicula')]
    public function index(): Response
    {

        return $this->render('pelicula/index.html.twig', [
            'controller_name' => 'PeliculaController',
        ]);
    }
}
