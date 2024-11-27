<?php

declare(strict_types=1);

namespace App\UI\Cli\Location;

use App\App\Command\Location\CreateLocation\CreateLocationSync;
use App\Domain\Write\Location\LocationDto;
use App\Infra\Persistence\Doctrine\ORM\Repository\LocationRepository;
use App\Infra\Persistence\Doctrine\ORM\Repository\RessourceRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'fleet:localize-vehicle',
    description: 'Create a Location for a Vehicle Command'
)]
class CreateLocationCli extends Command
{
    public function __construct(
        private readonly MessageBusInterface $message,
        private readonly RessourceRepository $ressourceRepository,
        private readonly ValidatorInterface $validator,
        private readonly LocationRepository $locationRepository,
    ) {
        parent::__construct('fleet:localize-vehicle');
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
            'Vehicule Plate Number'
        );

        $this->addArgument(
            'lat',
            InputArgument::REQUIRED,
            'Latitude',
        );

        $this->addArgument(
            'lng',
            InputArgument::REQUIRED,
            'Longitude',
        );

        $this->addArgument(
            'place_number',
            InputArgument::OPTIONAL,
            'Place number',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $ressource = $this->ressourceRepository->findOneByPlateNumber($input->getArgument('vehiclePlateNumber'), $input->getArgument('fleetid'));
            if (null === $ressource) {
                throw new \InvalidArgumentException('Ressource have not been found !');
            }

            // First Validation mandatory
            $regexOnlyNumberPossible = '/[0-9.]+/m';
            if (!preg_match_all($regexOnlyNumberPossible, $input->getArgument('lat'), $matches, PREG_SET_ORDER, 0)) {
                throw new \InvalidArgumentException('Lat is not valid !');
            }
            if (!preg_match_all($regexOnlyNumberPossible, $input->getArgument('lng'), $matches, PREG_SET_ORDER, 0)) {
                throw new \InvalidArgumentException('Lng is not valid !');
            }
            if (null !== $input->getArgument('place_number') && !preg_match_all($regexOnlyNumberPossible, $input->getArgument('place_number'), $matches, PREG_SET_ORDER, 0)) {
                throw new \InvalidArgumentException('place_number is not valid !');
            }

            $locationAlreadyExist = $this->locationRepository->findOneByLatLng(
                (string) $ressource->getId(),
                (float) $input->getArgument('lat'),
                (float) $input->getArgument('lng')
            );
            if (null !== $locationAlreadyExist) {
                throw new \InvalidArgumentException('Ressource already park there !');
            }

            $locationDto = new LocationDto(
                $ressource,
                floatval($input->getArgument('lat')),
                floatval($input->getArgument('lng')),
                null !== $input->getArgument('place_number') ? (int) $input->getArgument('place_number') : null
            );
            $violations = $this->validator->validate($locationDto);
            if (count($violations) > 0) {
                $message = '';
                foreach ($violations as $violation) {
                    $message .= $violation->getMessage();
                }
                throw new ValidatorException($message);
            }

            $this->message->dispatch(new CreateLocationSync($locationDto));
            $output->writeln('Location inserted !');
            echo (string) $locationDto;

            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }
    }
}
