<?php

declare(strict_types=1);

namespace Tests\Behat\Context;

use App\Infra\Persistence\Doctrine\ORM\Entity\Fleet;
use App\Infra\Persistence\Doctrine\ORM\Entity\User;
use App\Infra\Persistence\Doctrine\ORM\Repository\FleetRepository;
use App\Infra\Persistence\Doctrine\ORM\Repository\RessourceRepository;
use App\UI\Cli\Ressource\CreateRessourceCli;
use Behat\Behat\Context\Context;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\Behat\Utils\CliTesterUtils;
use Tests\Behat\Utils\FleetUtils;
use Tests\Behat\Utils\RessourceUtils;

class VehicleContext implements Context
{
    use CliTesterUtils;
    use FleetUtils;
    use RessourceUtils;

    private Fleet $myFleet;
    private Fleet $myOtherfleet;
    private string $plateNumber;

    public function __construct(
        private KernelInterface $kernel,
        private readonly MessageBusInterface $message,
        private readonly FleetRepository $fleetRepository,
        private readonly ValidatorInterface $validator,
        private readonly RessourceRepository $ressourceRepository,
    ) {
        // $this->kernel = $kernel;
    }

    /**
     * @AfterScenario
     */
    public function rollbackTransaction($event): void
    {
        $ressources = $this->ressourceRepository->findAll();
        foreach ($ressources as $ressource) {
            $this->ressourceRepository->remove($ressource);
        }
    }

    /**
     * @Given my fleet
     */
    public function myFleet(): void
    {
        $this->myFleet = $this->fleetRepository->find(new UuidV4($this->getMyFleet()));
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle(): void
    {
        $this->plateNumber = $this->getPlateNumberTrait();
    }

    /**
     * @Given I have registered this vehicle into my fleet
     *
     * @When I register this vehicle into my fleet
     * @When I try to register this vehicle into my fleet
     */
    public function iRegisterThisVehicleIntoMyFleet(): void
    {
        $this->setApplication();
        $this->addCommand(new CreateRessourceCli($this->message, $this->fleetRepository, $this->validator, $this->ressourceRepository));
        $this->setCommand('fleet:register-vehicle');
        $this->setOptions(['fleetid' => (string) $this->myFleet->getId(), 'vehiclePlateNumber' => $this->plateNumber]);

        try {
            $this->getTester($this->command)->execute($this->options);
        } catch (\Exception $exception) {
            $path = explode('\\', get_class($exception));
            $this->commandException = array_pop($path);
        }
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet(): void
    {
        $current = trim($this->commandTester->getDisplay());
        if (!str_contains($current, 'Ressource inserted !')) {
            throw new \RuntimeException('The command don\'t exit properly.');
        }
    }

    /**
     * @Then I should be informed this vehicle has already been registered into my fleet
     */
    public function iShouldBeInformedThisThisVehicleHasAlreadyBeenRegisteredIntoMyFleet(): void
    {
        $current = trim($this->commandTester->getDisplay());
        if (!str_contains($current, 'Ressource already registered on the fleet !')
            && Command::FAILURE !== $this->commandTester->getStatusCode()) {
            throw new \RuntimeException('The command don\'t exit properly.');
        }
    }

    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser(): void
    {
        $this->myOtherfleet = $this->fleetRepository->find(new UuidV4('e5fdbd56-d2ae-4940-889b-c573a8052ad4'));
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet(): void
    {
        $this->setApplication();
        $this->addCommand(new CreateRessourceCli($this->message, $this->fleetRepository, $this->validator, $this->ressourceRepository));
        $this->setCommand('fleet:register-vehicle');
        $this->setOptions(['fleetid' => (string) $this->myOtherfleet->getId(), 'vehiclePlateNumber' => $this->plateNumber]);

        try {
            $this->getTester($this->command)->execute($this->options);
        } catch (\Exception $exception) {
            $path = explode('\\', get_class($exception));
            $this->commandException = array_pop($path);
        }
    }
}
