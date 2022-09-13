<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsuariosFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $usuario = new User();
        $usuario->setEmail('n@gmail.com');
        $usuario->setPassword('123');
        $usuario->setRoles(['ROLE_USER']);
        $usuario->setPhoto('no.jpg');
        $manager->persist($usuario);

        $manager->flush();

        $this->addReference('usuario', $usuario);

    }
}
