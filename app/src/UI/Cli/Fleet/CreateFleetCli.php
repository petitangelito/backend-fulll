<?php

declare(strict_types=1);

namespace App\UI\Cli\Fleet;

use App\App\Command\Fleet\CreateFleet\CreateFleet;
use App\App\Command\Fleet\CreateFleetSync\CreateFleetSync;
use App\Domain\Write\Fleet\FleetDto;
use App\Infra\Persistence\Doctrine\ORM\Repository\UserRepository;
use MongoDB\Driver\Exception\CommandException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'fleet:create-fleet',
    description: 'Create Fleet Command'
)]
class CreateFleetCli extends Command
{
    private const DEFAULT = 'default';
    private const ASYNC = 'async';

    public function __construct(
        private readonly CreateFleet $createFleet,
        private readonly MessageBusInterface $message,
        private readonly UserRepository $userRepository,
        private readonly string $defaultTreatement = self::ASYNC,
    ) {
        parent::__construct('fleet:create-fleet');
    }

    public function configure(): void
    {
        $this->addArgument(
            'userid',
            InputArgument::REQUIRED,
            'User Uuid'
        );

        $this->addArgument(
            'label',
            InputArgument::OPTIONAL,
            'Label'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $user = $this->userRepository->find(Uuid::fromString($input->getArgument('userid')));
            $fleetDto = new FleetDto(
                $user,
                $input->getArgument('label')
            );

            switch ($this->defaultTreatement) {
                case self::DEFAULT:
                    $this->createFleet->create($fleetDto);
                    break;
                case self::ASYNC:
                    $this->message->dispatch(new CreateFleetSync($fleetDto));
                    break;
                default:
                    throw new CommandException();
            }

            echo (string) $fleetDto;

            return Command::SUCCESS;
        } catch (CommandException $exception) {
            echo sprintf('Command Error: %s ==> Probably missing the treatment method', $exception->getMessage());

            return Command::FAILURE;
        } catch (\Throwable $exception) {
            echo sprintf('Error: %s', $exception->getMessage());

            return Command::FAILURE;
        }
    }
}
