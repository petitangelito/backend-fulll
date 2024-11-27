<?php

namespace App\Infra\DataFixtures;

use App\Infra\Persistence\Doctrine\ORM\Entity\Fleet;
use App\Infra\Persistence\Doctrine\ORM\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class TestsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Users creation
        $user = (new User())->setId(new UuidV4('09a6f122-f67b-42c7-b6ef-8902a47e94aa'))
            ->setUsername('User 1')
            ->setEmail('User1@fulll.com')
            ->setPassword('User1')
            ->setCompany('Company 1')
            ->setCreatedAt(new \DateTimeImmutable('2024-11-25T10:00:00+00:00'))
            ->setUpdatedAt(new \DateTimeImmutable('2024-11-25T10:00:00+00:00'));
        $manager->persist($user);

        $fleet = (new Fleet())->setId(new UuidV4('2848175c-24b3-4826-be79-31a63c04ed22'))
            ->setUserId($user)
            ->setLabel('My Fleet test Label')
            ->setCreatedAt(new \DateTimeImmutable('2024-11-25T10:00:00+00:00'))
            ->setUpdatedAt(new \DateTimeImmutable('2024-11-25T10:00:00+00:00'));
        $manager->persist($fleet);

        $user = (new User())->setId(new UuidV4('df6a208d-abf3-4c9e-939e-9ca5276c6a3b'))
            ->setUsername('Another User')
            ->setEmail('AnotherUser@fulll.com')
            ->setPassword('AnotherUser')
            ->setCompany('Another Company')
            ->setCreatedAt(new \DateTimeImmutable('2024-11-25T10:00:00+00:00'))
            ->setUpdatedAt(new \DateTimeImmutable('2024-11-25T10:00:00+00:00'));
        $manager->persist($user);

        $fleet = (new Fleet())->setId(new UuidV4('e5fdbd56-d2ae-4940-889b-c573a8052ad4'))
            ->setUserId($user)
            ->setLabel('My Other Fleet test Label')
            ->setCreatedAt(new \DateTimeImmutable('2024-11-25T10:00:00+00:00'))
            ->setUpdatedAt(new \DateTimeImmutable('2024-11-25T10:00:00+00:00'));
        $manager->persist($fleet);

        $manager->flush();
    }
}
