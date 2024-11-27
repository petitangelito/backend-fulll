<?php

namespace Tests\Behat\Utils;

trait FleetUtils
{
    private string $myFleetTrait;

    public function getMyFleet(): string
    {
        $this->myFleetTrait = '2848175c-24b3-4826-be79-31a63c04ed22';

        return $this->myFleetTrait;
    }
}
