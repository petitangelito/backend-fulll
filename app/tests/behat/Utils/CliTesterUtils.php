<?php

namespace Tests\Behat\Utils;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

trait CliTesterUtils
{
    private Application $application;
    private Command $command;
    private CommandTester $commandTester;
    private string $commandException;
    private array $options;

    private function setApplication(): void
    {
        $this->application = new Application($this->kernel);
    }

    private function addCommand(Command $command): void
    {
        $this->application->add($command);
    }

    private function setCommand(string $commandName): void
    {
        $this->command = $this->application->find($commandName);
    }

    private function setOptions(array $options): void
    {
        $this->options = $options;
    }

    private function getTester(Command $command): CommandTester
    {
        $this->commandTester = new CommandTester($command);

        return $this->commandTester;
    }
}
