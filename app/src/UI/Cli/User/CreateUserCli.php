<?php

declare(strict_types=1);

namespace App\UI\Cli\User;

use App\App\Command\User\CreateUser\CreateUser;
use App\Domain\Write\User\UserDto;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'user:create-user',
    description: 'Create User Command'
)]
class CreateUserCli extends Command
{
    public function __construct(private readonly CreateUser $createUser)
    {
        parent::__construct('user:create-user');
    }

    public function configure(): void
    {
        $this->addArgument(
            'username',
            InputArgument::REQUIRED,
            'Username'
        );
        $this->addArgument(
            'email',
            InputArgument::REQUIRED,
            'Email'
        );
        $this->addArgument(
            'password',
            InputArgument::REQUIRED,
            'Password'
        );
        $this->addArgument(
            'company',
            InputArgument::OPTIONAL,
            'Company'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $userDTO = new UserDto(
                username: $input->getArgument('username'),
                password: $input->getArgument('password'),
                email: $input->getArgument('email'),
                company: $input->getArgument('company')
            );

            $this->createUser->create($userDTO);

            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            echo sprintf('Error: %s', $exception->getMessage());

            return Command::FAILURE;
        }
    }
}
