<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infra\Persistence\Doctrine\ORM\Entity;

use App\Infra\Persistence\Doctrine\ORM\Entity\User;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\UuidV4;

class UserTest extends KernelTestCase
{
    public function getUserEntity(): User
    {
        $faker = Factory::create();

        return (new User())->setId(new UuidV4())
                            ->setUsername($faker->userName())
                            ->setEmail($faker->email())
                            ->setPassword($faker->password())
                            ->setCompany($faker->company())
                            ->setCreatedAt(new \DateTimeImmutable())
                            ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function testUserIsValid(): void
    {
        $user = $this->getUserEntity();

        $errors = static::getContainer()->get('validator')->validate($user);
        $this->assertTrue(0 == $errors->count());
    }

    public function testUserEmailIsNotAnEmail(): void
    {
        $user = $this->getUserEntity();
        $user->setEmail('email');
        $errors = static::getContainer()->get('validator')->validate($user);
        $this->assertTrue(1 == $errors->count());
    }
}
