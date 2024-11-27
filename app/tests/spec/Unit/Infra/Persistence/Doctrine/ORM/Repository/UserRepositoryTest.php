<?php

namespace App\Tests\Unit\Infra\Persistence\Doctrine\ORM\Repository;

use App\Infra\Persistence\Doctrine\ORM\Entity\User;
use App\Infra\Persistence\Doctrine\ORM\Repository\UserRepository;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\UuidV4;

class UserRepositoryTest extends KernelTestCase
{
    public function testInsertAndRemove(): void
    {
        $faker = Factory::create();
        $user = (new User())->setId(new UuidV4())
            ->setUsername($faker->userName())
            ->setEmail($faker->email())
            ->setPassword($faker->password())
            ->setCompany($faker->company())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

        $container = static::getContainer();
        $usersContainer = $container->get(UserRepository::class);
        $usersContainer->save($user);
        $this->assertEquals(3, $usersContainer->count([]));

        $usersContainer->remove($user);
        $this->assertEquals(2, $usersContainer->count([]));
    }
}
