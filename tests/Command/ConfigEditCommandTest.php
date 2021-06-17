<?php

namespace Tests\Command;

use Laraboot\Commands\EditConfigFileCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigEditCommandTest extends KernelTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $fixture = self::fixturesFolder() . '/app/config/custom.php';
        $destination = self::getSampleAppPath() . '/config/custom.php';
        file_put_contents($destination, file_get_contents($fixture), LOCK_EX);
    }

    /**
     * A set of config path values
     */
    public function pathAndValuesDataProvider(): ?\Generator
    {
        yield ['config.custom/models_namespace', 'Models'];
        yield ['config.database/connections.mysql.prefix', "la_"];
        yield ['config.hashing/bcrypt.rounds', "50"];
        yield ['config.hashing/argon.memory', "2048"];
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

    protected function assertPathExpressionMatchValue(string $path, string $value)
    {
        $slash_pos = stripos($path, '/', 0);
        $file = substr($path, 0, $slash_pos);
        $file = str_replace('.', '/', $file);
        $key = substr($path, $slash_pos + 1);
        $res = require self::getSampleAppPath() . "/$file.php";

        $this->assertArrayHasPathKey($key, $res, $value);
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
            '-d' => self::getSampleAppPath()
        ]));

    }

    protected static function getSampleAppPath(): string
    {
        return dirname(dirname(__DIR__)) . '/sample-apps/laravel-app';
    }

    protected static function fixturesFolder(): string
    {
        return dirname(dirname(__DIR__)) . '/fixtures';
    }

    /**
     * @throws \Exception
     */
    protected function assertArrayHasPathKey($key, array $res, $value = null)
    {
        if (substr_count($key, '.') > 0) {
            $tokens = explode('.', $key);
            $token_len = \count($tokens);
            $current = $res[array_shift($tokens)];
            reset($tokens);
            $i = 1;
            while ($t = array_shift($tokens)) {
                if (!isset($current[$t])) {
                    throw new \Exception(sprintf("Array doesn't contain key %s \n %s", $t, print_r($current, true)));
                }
                if (++$i == $token_len) {
                    $this->assertArrayHasKey($t, $current);
                    if ($value) {
                        $this->assertEquals($current[$t], $value);
                    }
                }
            }
        } else {
            $this->assertArrayHasKey($key, $res);
            if ($value) {
                $this->assertEquals($res[$key], $value);
            }
        }
    }
}