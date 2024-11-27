<?php

namespace Tests\Behat\Utils;

trait RessourceUtils
{
    private string $plateNumberTrait;

    public function getPlateNumberTrait(): string
    {
        $this->plateNumberTrait = 'AB-123-CD';

        return $this->plateNumberTrait;
    }
}
