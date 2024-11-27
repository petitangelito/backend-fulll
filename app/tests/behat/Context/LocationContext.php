<?php

declare(strict_types=1);

namespace Tests\Behat\Context;

use App\Infra\Persistence\Doctrine\ORM\Entity\Fleet;
use App\Infra\Persistence\Doctrine\ORM\Entity\Location;
use App\Infra\Persistence\Doctrine\ORM\Repository\FleetRepository;
use App\Infra\Persistence\Doctrine\ORM\Repository\LocationRepository;
use App\Infra\Persistence\Doctrine\ORM\Repository\RessourceRepository;
use App\UI\Cli\Location\CreateLocationCli;
use Behat\Behat\Context\Context;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\Behat\Utils\CliTesterUtils;
use Tests\Behat\Utils\FleetUtils;
use Tests\Behat\Utils\RessourceUtils;

class LocationContext implements Context
{
    use CliTesterUtils;
    use FleetUtils;
    use RessourceUtils;
    private readonly Location $location;
    private readonly Fleet $aFleet;
    private readonly string $plateNumber;

    public function __construct(
        private KernelInterface $kernel,
        private readonly MessageBusInterface $message,
        private readonly RessourceRepository $ressourceRepository,
        private readonly ValidatorInterface $validator,
        private readonly FleetRepository $fleetRepository,
        private readonly LocationRepository $locationRepository,
    ) {
    }

    /**
     * @Given a location
     */
    public function aLocation(): void
    {
        $faker = Factory::create();
        $this->location = (new Location())->setId(new UuidV4('89ef1d80-4312-445f-91f0-690e65b9e801'))
                            ->setLat($faker->latitude())
                            ->setLng($faker->longitude())
                            ->setPlaceNumber($faker->numberBetween(0, 150));
    }

    /**
     * @When I park my vehicle at this location
     * @When I try to park my vehicle at this location
     *
     * @Given my vehicle has been parked into this location
     */
    public function iParkMyVehicleAtThisLocation(): void
    {
        $this->setApplication();
        $this->addCommand(new CreateLocationCli(
            $this->message,
            $this->ressourceRepository,
            $this->validator,
            $this->locationRepository
        ));
        $this->setCommand('fleet:localize-vehicle');

        $this->setOptions([
            'fleetid' => $this->getMyFleet(),
            'vehiclePlateNumber' => $this->getPlateNumberTrait(),
            'lat' => (string) $this->location->getLat(),
            'lng' => (string) $this->location->getLng(),
        ]);

        try {
            $this->getTester($this->command)->execute($this->options);
        } catch (\Exception $exception) {
            $path = explode('\\', get_class($exception));
            $this->commandException = array_pop($path);
        }
    }

    /**
     * @Then the known location of my vehicle should verify this location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation(): void
    {
        $current = trim($this->commandTester->getDisplay());
        if (!str_contains($current, 'Location inserted !')) {
            throw new \RuntimeException('The command don\'t exit properly.');
        }
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation(): void
    {
        $current = trim($this->commandTester->getDisplay());
        if (!str_contains($current, 'Ressource already park there !')
            && Command::FAILURE !== $this->commandTester->getStatusCode()) {
            throw new \RuntimeException('The command don\'t exit properly.');
        }
    }

    /**
     * @AfterScenario
     */
    public function rollbackTransaction($event): void
    {
        $locations = $this->locationRepository->findAll();
        foreach ($locations as $location) {
            $this->locationRepository->remove($location);
        }
    }
}
