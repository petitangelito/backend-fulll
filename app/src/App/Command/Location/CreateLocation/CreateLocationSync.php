<?php

declare(strict_types=1);

namespace App\App\Command\Location\CreateLocation;

use App\Domain\Write\Location\LocationDto;

final class CreateLocationSync
{
    public function __construct(private readonly LocationDto $locationDto)
    {
    }

    public function getLocationDto(): LocationDto
    {
        return $this->locationDto;
    }
}
