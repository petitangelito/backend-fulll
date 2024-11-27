<?php

declare(strict_types=1);

namespace App\App\Query\User\GetUser;

use App\Infra\Persistence\Doctrine\ORM\Entity\User;
use App\Infra\Persistence\Doctrine\ORM\Repository\UserRepository;
use Symfony\Component\Uid\Uuid;

final readonly class GetUser
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function getUser(Uuid $id): User
    {
        return $this->userRepository->find($id);
    }
}
