<?php

namespace Tests\Command;

use Laraboot\Commands\EditConfigFileCommand;
use PhpParser\{ParserFactory, PrettyPrinter\Standard};
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

        $fixture = self::fixturesFolder() . '/app/config/env.php';
        $destination = self::getSampleAppPath() . '/config/env.php';
        file_put_contents($destination, file_get_contents($fixture), LOCK_EX);
    }

    /**
     * A set of config path values
     */
    public static function pathAndValuesDataProvider(): ?\Generator
    {
        yield ['config.custom/models_namespace', 'Models'];
        yield ['config.custom/path.a.b.c.d', "true"];
        yield ['config.hashing/bcrypt.rounds', "50"];
        yield ['config.hashing/argon.memory', "2048"];
        yield ['config.app/name', 'newName'];
        yield ['config.app/env', 'alpha'];
        // one more time
        yield ['config.app/name', 'Gregory'];
        yield ['config.app/env', 'delta'];
    }

    /**
     * A set of config path, values, flags and expectations
     */
    public static function pathValuesAndFlagsDataProvider(): ?\Generator
    {
        yield ['config.env/env.a.b.c.d',
            "500",
            ['-e' => "A|B|C|D"],
            "return ['env' => ['a' => ['b' => ['c' => ['d' => env('A', env('B', env('C', env('D', '500'))))]]]]];"];
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

        $this->executeCommand($inputs);

        $this->assertPathExpressionMatchValue($path, $value);

    }

    /**
     * @dataProvider pathValuesAndFlagsDataProvider
     *
     * This test provides all the arguments required by the command, so the
     * command runs non-interactively and it won't ask for any argument.
     */
    public function testChangeConfigValueChainedNonInteractive(string $path, string $value, array $options, string $expect): void
    {
        $inputs = ['path' => $path, 'value' => $value ?? 'newValue'];

        $this->executeCommand($inputs, $options);

        list(, $code) = $this->getFilePathAndContentsFromConfigPath($path);

        $this->assertEquals($expect, $this->printCode($code));
    }

    protected function assertPathExpressionMatchValue(string $path, string $value)
    {
        list($key, $res) = $this->getRealPathFromConfigPath($path);

        $this->assertArrayHasPathKey($key, $res, $value);
    }

    /**
     * @param array $inputs
     * @param array $options
     */
    private function executeCommand(array $inputs, array $options = []): void
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
        ], $options));

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
                $current = $current[$t];
            }
        } else {
            $this->assertArrayHasKey($key, $res);
            if ($value) {
                $this->assertEquals($res[$key], $value);
            }
        }
    }

    /**
     * @param string $path
     * @return array
     */
    protected function getRealPathFromConfigPath(string $path): array
    {
        $slash_pos = stripos($path, '/', 0);
        $file = substr($path, 0, $slash_pos);
        $file = str_replace('.', '/', $file);
        $key = substr($path, $slash_pos + 1);
        $res = require self::getSampleAppPath() . "/$file.php";
        return [$key, $res];
    }

    protected function getFilePathAndContentsFromConfigPath(string $path): array
    {
        $slash_pos = stripos($path, '/', 0);
        $file = substr($path, 0, $slash_pos);
        $file = str_replace('.', '/', $file);
        $res = file_get_contents(self::getSampleAppPath() . "/$file.php");
        return [$file, $res];
    }

    private function printCode(string $code): string
    {
        $parser = (new \PhpParser\ParserFactory())->createForHostVersion();
        $statements = $parser->parse($code);
        $this->assertIsArray($statements);
        $printer = new Standard();
        return $printer->prettyPrint($statements);
    }
}