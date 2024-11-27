<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infra\Persistence\Doctrine\ORM\Entity;

use App\Domain\Write\Ressource\RessourceDto;
use App\Infra\Persistence\Doctrine\ORM\Entity\Fleet;
use App\Infra\Persistence\Doctrine\ORM\Entity\Ressource;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\UuidV4;

class RessourceTest extends KernelTestCase
{
    public function getRessourceEntity(): Ressource
    {
        $faker = Factory::create();

        return (new Ressource())->setId(new UuidV4())
                            ->setFleetId(new Fleet())
                            ->setMode($faker->randomElement(RessourceDto::MODES))
                            ->setPlateNumber($faker->text(20))
                            ->setCreatedAt(new \DateTimeImmutable())
                            ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function testRessourceIsValid(): void
    {
        $ressource = $this->getRessourceEntity();

        $errors = static::getContainer()->get('validator')->validate($ressource);
        $this->assertTrue(0 == $errors->count());
    }

    public function testRessourceIsBlank(): void
    {
        $ressource = $this->getRessourceEntity()->setPlateNumber('');

        $errors = static::getContainer()->get('validator')->validate($ressource);
        $this->assertTrue(2 == $errors->count());
    }

    public function testRessourceIsOnlyOneChar(): void
    {
        $ressource = $this->getRessourceEntity()->setPlateNumber('a');

        $errors = static::getContainer()->get('validator')->validate($ressource);
        $this->assertTrue(1 == $errors->count());
    }
}
