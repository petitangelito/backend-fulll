<?php

namespace App\Tests\Unit\Infra\Persistence\Doctrine\ORM\Repository;

use App\Infra\Persistence\Doctrine\ORM\Entity\Fleet;
use App\Infra\Persistence\Doctrine\ORM\Repository\FleetRepository;
use App\Infra\Persistence\Doctrine\ORM\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\UuidV4;

class FleetRepositoryTest extends KernelTestCase
{
    public function testInsertAndRemove(): void
    {
        $usersContainer = static::getContainer()->get(UserRepository::class);
        $user = $usersContainer->find('09a6f122-f67b-42c7-b6ef-8902a47e94aa');

        $fleet = (new Fleet())
            ->setId(new UuidV4())
            ->setUserId($user)
            ->setLabel('Test fleet')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

        $fleetContainer = static::getContainer()->get(FleetRepository::class);
        $fleetContainer->save($fleet);
        $this->assertEquals(3, $fleetContainer->count([]));

        $fleetContainer->remove($fleet);
        $this->assertEquals(2, $fleetContainer->count([]));
    }
}
