<?php

namespace Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigEditCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        self::bootKernel();

        // this uses a special testing container that allows you to fetch private services
        $command = self::$container->get(CommandTester::class);
        $command->setApplication(new Application(self::$kernel));
//
//        $commandTester = new CommandTester($command);
//        $commandTester->setInputs($inputs);
//        $commandTester->execute($arguments);
    }
}