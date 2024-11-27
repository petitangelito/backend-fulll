<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infra\Persistence\Doctrine\ORM\Entity;

use App\Infra\Persistence\Doctrine\ORM\Entity\Location;
use App\Infra\Persistence\Doctrine\ORM\Entity\Ressource;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\UuidV4;

class LocationTest extends KernelTestCase
{
    public function getLocationEntity(): Location
    {
        $faker = Factory::create();
        return (new Location())->setId(new UuidV4())
                            ->setRessourceId(new Ressource())
                            ->setLat($faker->latitude())
                            ->setLng($faker->longitude())
                            ->setPlaceNumber($faker->numberBetween(0, 150))
                            ->setCreatedAt(new \DateTimeImmutable())
                            ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function testLocationIsValid(): void
    {
        $location = $this->getLocationEntity();

        $errors = static::getContainer()->get('validator')->validate($location);
        $this->assertTrue(0 == $errors->count());
    }

    public function testLocationLatitudeIsOffRange(): void
    {
        $location = $this->getLocationEntity();

        $location->setLat(-91);
        $errors = static::getContainer()->get('validator')->validate($location);
        $this->assertTrue(1 == $errors->count());

        $location->setLat(91);
        $errors = static::getContainer()->get('validator')->validate($location);
        $this->assertTrue(1 == $errors->count());
    }

    public function testLocationLongitudeIsOffRange(): void
    {
        $location = $this->getLocationEntity();

        $location->setLng(-181);
        $errors = static::getContainer()->get('validator')->validate($location);
        $this->assertTrue(1 == $errors->count());

        $location->setLng(181);
        $errors = static::getContainer()->get('validator')->validate($location);
        $this->assertTrue(1 == $errors->count());
    }
}
