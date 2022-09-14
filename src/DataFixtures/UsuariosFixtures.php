<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuariosFixtures extends Fixture
{
    private $userPasswordHasher;
    public function __construct( UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $usuario = new User();
        $usuario->setEmail('n@gmail.com');
        $hashedPassword = $this->userPasswordHasher->hashPassword($usuario, 'n123');
        $usuario->setPassword($hashedPassword);
        $usuario->setRoles(['ROLE_USER']);
        $usuario->setPhoto('no.jpg');
        $manager->persist($usuario);

        $manager->flush();

        $this->addReference('usuario', $usuario);

    }
}
