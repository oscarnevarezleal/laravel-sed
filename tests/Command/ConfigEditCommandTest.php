<?php

namespace Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use Laraboot\Commands\EditConfigFileCommand;

class ConfigEditCommandTest extends KernelTestCase
{
    /**
     * A set of config path values
     */
    public function pathAndValuesDataProvider(): ?\Generator
    {
        yield ['config.app/name', 'newName'];
        yield ['config.app/env', 'alpha'];
        // one more time
        yield ['config.app/name', 'Gregory'];
        yield ['config.app/env', 'delta'];
    }
    
    /**
     * @dataProvider pathAndValuesDataProvider
     *
     * This test provides all the arguments required by the command, so the
     * command runs non-interactively and it won't ask for any argument.
     */
    public function testChangeConfigValueNonInteractive(string $path, string $value): void
    {
        $inputs = ['path' => $path, 'value' => $value ?? 'newValue'];

        $this->executeCommand([], $inputs);

        $this->assertPathExpressionMatchValue($path, $value);
    }
    
    protected function assertPathExpressionMatchValue(string $path, string $value){
        $slash_pos = stripos($path, '/', 0);
        $file = substr($path, 0, $slash_pos);
        $file = str_replace('.', '/', $file);
        $key = substr($path, $slash_pos + 1);
        $res = require $this->getSampleAppPath()."/$file.php";

        $this->assertArrayHasKey($key, $res);
        $this->assertEquals($res[$key], $value);
    }
    
    private function executeCommand(array $arguments, array $inputs): void
    {
        self::bootKernel();
        
        // this uses a special testing container that allows you to fetch private services
        $command = self::$container->get(EditConfigFileCommand::class);
        $command->setApplication(new Application(''));

        $commandTester = new CommandTester($command);
        // $commandTester->setInputs($inputs);
        
        $commandTester->execute(array_merge($inputs, [
            // pass arguments to the helper
            '-d' => $this->getSampleAppPath()
        ]));

    }
    
    protected function getSampleAppPath():string{
        return dirname(__DIR__) . '/../sample-apps/laravel-app';
    }
}