<?php

namespace App\App\Command\User\DeleteUser;

use App\Infra\Persistence\Doctrine\ORM\Repository\UserRepository;
use Symfony\Component\Uid\Uuid;

class DeleteUser
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function delete(Uuid $uuid): void
    {
        $user = $this->userRepository->find($uuid);
        $this->userRepository->remove($user, true);
    }
}
