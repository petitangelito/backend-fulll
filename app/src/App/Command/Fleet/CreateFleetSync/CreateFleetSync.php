<?php

declare(strict_types=1);

namespace App\App\Command\Fleet\CreateFleetSync;

use App\Domain\Write\Fleet\FleetDto;

final class CreateFleetSync
{
    public function __construct(
        private readonly FleetDto $fleetDto,
    ) {
    }

    public function getFleetDto(): FleetDto
    {
        return $this->fleetDto;
    }
}
