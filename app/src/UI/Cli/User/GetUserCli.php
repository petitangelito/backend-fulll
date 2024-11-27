<?php

declare(strict_types=1);

namespace App\UI\Cli\User;

use App\App\Query\User\GetUser\GetUser;
use App\Domain\Read\User\UserDto;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'user:get-user',
    description: 'Get User information Command'
)]
class GetUserCli extends Command
{
    public function __construct(private readonly GetUser $getUser)
    {
        parent::__construct('user:get-user');
    }

    public function configure(): void
    {
        $this->addArgument(
            'id',
            InputArgument::REQUIRED,
            'User ID'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $user = $this->getUser->getUser(Uuid::fromString($input->getArgument('id')));
            $userDto = new UserDto(
                $user->getId(),
                $user->getUsername(),
                $user->getPassword(),
                $user->getEmail(),
                $user->getCompany()
            );
            echo $userDto;

            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            echo sprintf('Error: %s', $exception->getMessage());

            return Command::FAILURE;
        }
    }
}
