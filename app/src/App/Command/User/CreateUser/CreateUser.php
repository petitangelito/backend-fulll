<?php

namespace App\App\Command\User\CreateUser;

use App\Domain\Write\User\UserDto;
use App\Infra\Persistence\Doctrine\ORM\Entity\User;
use App\Infra\Persistence\Doctrine\ORM\Repository\UserRepository;

final readonly class CreateUser
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function create(UserDto $createUserDto): User
    {
        $user = new User();
        $user->setId($createUserDto->getId());
        $user->setUsername($createUserDto->username);
        $user->setEmail($createUserDto->email);
        $user->setPassword($createUserDto->password);
        $user->setCompany($createUserDto->company);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->userRepository->save($user);

        return $user;
    }
}
