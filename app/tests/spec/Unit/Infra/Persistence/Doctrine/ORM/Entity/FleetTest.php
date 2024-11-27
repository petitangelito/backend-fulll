<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infra\Persistence\Doctrine\ORM\Entity;

use App\Infra\Persistence\Doctrine\ORM\Entity\Fleet;
use App\Infra\Persistence\Doctrine\ORM\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\UuidV4;

class FleetTest extends KernelTestCase
{
    public function getFleetEntity(): Fleet
    {
        return (new Fleet())->setId(new UuidV4())
                            ->setUserId(new User())
                            ->setLabel('Test fleet')
                            ->setCreatedAt(new \DateTimeImmutable())
                            ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function testFleetIsValid(): void
    {
        $fleet = $this->getFleetEntity();

        $errors = static::getContainer()->get('validator')->validate($fleet);
        $this->assertTrue(0 == $errors->count());
    }

    public function testFleetLabelIsOnlyOneChar(): void
    {
        $fleet = $this->getFleetEntity()->setLabel('T');

        $errors = static::getContainer()->get('validator')->validate($fleet);
        $this->assertTrue(1 == $errors->count());
    }

    public function testFleetLabelIsBlank(): void
    {
        $fleet = $this->getFleetEntity()->setLabel('');

        $errors = static::getContainer()->get('validator')->validate($fleet);
        $this->assertTrue(2 == $errors->count());
    }
}
