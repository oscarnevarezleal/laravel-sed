<?php

namespace Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use Laraboot\Commands\EditConfigFileCommand;

class ConfigEditCommandTest extends KernelTestCase
{

    protected function setUp(): void
    {
        exec('stty 2>&1', $output, $exitcode);
        $isSttySupported = 0 === $exitcode;

        if ('Windows' === PHP_OS_FAMILY || !$isSttySupported) {
            $this->markTestSkipped('`stty` is required to test this command.');
        }
    }
    
    /**
     * A set of config path values
     */
    public function pathDataProvider(): ?\Generator
    {
        yield ['config.name'];
        yield ['config.env', 'test'];
    }
    
    /**
     * @dataProvider pathDataProvider
     *
     * This test provides all the arguments required by the command, so the
     * command runs non-interactively and it won't ask for any argument.
     */
    public function testChangeConfigValueNonInteractive(string $path, string $value = null): void
    {
        $sample_app_dir = dirname(__DIR__).'/../sample-apps/app';
        
        $arguments = ['-d' => $sample_app_dir];
        
        $inputs = ['path' => $path, 'value' => $value ?? 'newValue'];
        
        $this->executeCommand($arguments, $inputs);

        // $this->assertUserCreated($isAdmin);
    }
    
    private function executeCommand(array $arguments, array $inputs): void
    {
        self::bootKernel();
        
        print_r($arguments);
        print_r($inputs);

        // this uses a special testing container that allows you to fetch private services
        $command = self::$container->get(EditConfigFileCommand::class);
        $command->setApplication(new Application(''));

        $commandTester = new CommandTester($command);
        $commandTester->setInputs([0, 1]);
        $commandTester->execute($arguments);
    }
}