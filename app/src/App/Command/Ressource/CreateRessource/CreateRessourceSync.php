<?php

declare(strict_types=1);

namespace App\App\Command\Ressource\CreateRessource;

use App\Domain\Write\Ressource\RessourceDto;

final class CreateRessourceSync
{
    public function __construct(private readonly RessourceDto $ressourceDto)
    {
    }

    public function getRessourceDto(): RessourceDto
    {
        return $this->ressourceDto;
    }
}
