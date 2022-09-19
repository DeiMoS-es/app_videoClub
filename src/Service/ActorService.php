<?php


namespace App\Service;


use App\Entity\Actor;
use App\Repository\ActorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ActorService extends AbstractController
{
    private $em;
    private $actorRepository;

    public function __construct(EntityManagerInterface $em, ActorRepository $actorRepository)
    {
        $this->em = $em;
        $this->actorRepository = $actorRepository;
    }

    public function buscarTodos(){
        return $this->actorRepository->findAll();
    }

    public function saveActor(Actor $actor){
        $this->em->persist($actor);
        $this->em->flush();
    }

    public function insertarFoto(Actor $actor, $fotoActor){
        if ($fotoActor){
            $newNombreFoto = uniqid().'.'.$fotoActor->guessExtension();
            try {
                $fotoActor->move(
                    $this->getParameter('foto_actores'),
                    $newNombreFoto
                );
                $actor->setFotoActor($newNombreFoto);
            } catch (FileException $e) {
                throw new \Exception('Problema con el archivo');
            }
        }else{
            $actor->setFotoActor('default.jpg');
        }

    }
}