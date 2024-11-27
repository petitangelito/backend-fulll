<?php

declare(strict_types=1);

namespace App\App\Query\Fleet\GetFleet;

use App\Infra\Persistence\Doctrine\ORM\Entity\Fleet;
use App\Infra\Persistence\Doctrine\ORM\Repository\FleetRepository;
use Symfony\Component\Uid\Uuid;

final readonly class GetFleet
{
    public function __construct(private readonly FleetRepository $fleetRepository)
    {
    }

    public function getFleet(Uuid $id): Fleet
    {
        return $this->fleetRepository->find($id);
    }
}
