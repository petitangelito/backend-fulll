<?php

declare(strict_types=1);

namespace App\UI\Cli\Ressource;

use App\App\Command\Ressource\CreateRessource\CreateRessourceSync;
use App\Domain\Write\Ressource\RessourceDto;
use App\Infra\Persistence\Doctrine\ORM\Repository\FleetRepository;
use App\Infra\Persistence\Doctrine\ORM\Repository\RessourceRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'fleet:register-vehicle',
    description: 'Create a Vehicle for a Fleet Command'
)]
class CreateRessourceCli extends Command
{
    public function __construct(
        private readonly MessageBusInterface $message,
        private readonly FleetRepository $fleetRepository,
        private readonly ValidatorInterface $validator,
        private readonly RessourceRepository $ressourceRepository,
    ) {
        parent::__construct('fleet:register-vehicle');
    }

    public function configure(): void
    {
        $this->addArgument(
            'fleetid',
            InputArgument::REQUIRED,
            'Fleet Uuid'
        );

        $this->addArgument(
            'vehiclePlateNumber',
            InputArgument::REQUIRED,
            'Vehicle Plate Number'
        );

        $this->addArgument(
            'mode',
            InputArgument::OPTIONAL,
            sprintf('Vehicle Mode ==> in [%s]', implode(',', RessourceDto::MODES)),
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $fleet = $this->fleetRepository->find(Uuid::fromString($input->getArgument('fleetid')));
            $ressourceAlreadyRegistered = $this->ressourceRepository->findOneByPlateNumber($input->getArgument('vehiclePlateNumber'), $input->getArgument('fleetid'));
            if (null !== $ressourceAlreadyRegistered) {
                echo 'Ressource already registered on the fleet !';
                throw new \InvalidArgumentException('Ressource already registered on the fleet !');
            }
            $ressourceDto = new RessourceDto(
                $fleet,
                $input->getArgument('vehiclePlateNumber'),
                $input->getArgument('mode'),
            );
            $violations = $this->validator->validate($ressourceDto);
            if (count($violations) > 0) {
                $message = '';
                foreach ($violations as $violation) {
                    $message .= $violation->getMessage();
                }
                throw new ValidatorException($message);
            }
            $this->message->dispatch(new CreateRessourceSync($ressourceDto));
            $output->writeln('Ressource inserted !');
            echo (string) $ressourceDto;

            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }
    }
}
