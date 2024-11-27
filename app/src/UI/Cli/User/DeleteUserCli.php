<?php

namespace App\UI\Cli\User;

use App\App\Command\User\DeleteUser\DeleteUser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'user:delete-user',
    description: 'Delete User Command'
)]
class DeleteUserCli extends Command
{
    public function __construct(private readonly DeleteUser $deleteUser)
    {
        parent::__construct('user:delete-user');
    }

    public function configure(): void
    {
        $this->addArgument(
            'id',
            InputArgument::REQUIRED,
            'User Uuid'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->deleteUser->delete(Uuid::fromString($input->getArgument('id')));

            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            echo sprintf('Error: %s', $exception->getMessage());

            return Command::FAILURE;
        }
    }
}
