<?php

namespace App\DataFixtures;

use App\Entity\Pelicula;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PeliculasFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $pelicula1 = new Pelicula();
        $pelicula1->setTitulo('The Dark Knight');
        $pelicula1->setDescripcion('Esta es la descripcion de Dark Knight');
        $pelicula1->setTipo('Accion');
        $pelicula1->setFoto('https://i0.wp.com/applauss.com/wp-content/uploads/2018/07/the-dark-knight.jpg?resize=770%2C513&ssl=1');
        $pelicula1->setUrl('no tiene de momento');
        $pelicula1->setFechaAlta(new \DateTime());
        $pelicula1->addActore($this->getReference('actor_1'));
        $pelicula1->addActore($this->getReference('actor_2'));
        //$pelicula1->setUser($this->getReference('usuario'));
        $manager->persist($pelicula1);

        $pelicula2 = new Pelicula();
        $pelicula2->setTitulo('The Avenger: Endgame');
        $pelicula2->setDescripcion('Esta es la descripcion de he Avenger');
        $pelicula2->setTipo('Accion');
        $pelicula2->setFoto('https://ichef.bbci.co.uk/news/800/cpsprodpb/BF0D/production/_106090984_2e39b218-c369-452e-b5be-d2476f9d8728.jpg.webp');
        $pelicula2->setUrl('no tiene de momento');
        $pelicula2->setFechaAlta(new \DateTime());
        $pelicula2->addActore($this->getReference('actor_3'));
        $pelicula2->addActore($this->getReference('actor_4'));
       // $pelicula2->setUser($this->getReference('usuario'));
        $manager->persist($pelicula2);

        $manager->flush();
    }
}
