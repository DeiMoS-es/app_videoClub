<?php


namespace App\Service;


use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HasPasswordService
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function encriptarPassword(string $password, $user){
        $hashedPassword = $this->passwordHasher->hashPassword($user,$password);
        return $hashedPassword;
    }

}