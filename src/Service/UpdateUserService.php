<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;

class UpdateUserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke (int $id):User
    {
        if(null !== $user = $this->userRepository->findOneBy($id)){

        }
    }
}