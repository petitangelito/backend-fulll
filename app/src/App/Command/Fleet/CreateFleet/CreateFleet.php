<?php

declare(strict_types=1);

namespace App\App\Command\Fleet\CreateFleet;

use App\Domain\Write\Fleet\FleetDto;
use App\Infra\Persistence\Doctrine\ORM\Entity\Fleet;
use App\Infra\Persistence\Doctrine\ORM\Repository\FleetRepository;
use Symfony\Component\Uid\Uuid;

final readonly class CreateFleet
{
    public function __construct(
        private FleetRepository $fleetRepository,
    ) {
    }

    public function create(FleetDto $fleetDto): Uuid
    {
        $fleet = new Fleet();
        $fleet->setId($fleetDto->getId());
        $fleet->setUserId($fleetDto->user);
        $fleet->setLabel($fleetDto->label);
        $fleet->setCreatedAt(new \DateTimeImmutable());
        $fleet->setUpdatedAt(new \DateTimeImmutable());

        $this->fleetRepository->save($fleet);

        return $fleet->getId();
    }
}
