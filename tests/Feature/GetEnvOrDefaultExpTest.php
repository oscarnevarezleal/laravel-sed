<?php

use Laraboot\Exp\GetEnvOrDefaultExp;
use PhpParser\{NodeTraverser, PrettyPrinter};
use PHPUnit\Framework\TestCase;

class GetEnvOrDefaultExpTest extends TestCase
{
    /**
     * Assert that given a list of environment variables names,
     * the result expression contains a nested call to _env_ function,
     * having the deepest call a default value.
     * E.g. env('ORGANIZATION_NAME', env('PROJECT_NAME', env('APP_NAME', 'MyApp')))
     */
    public function testChainOfEnvsOrDefaultExp()
    {
        $traverser = new NodeTraverser;
        $prettyPrinter = new PrettyPrinter\Standard;

        $envList = [
            'ORGANIZATION_NAME',
            'PROJECT_NAME',
            'APP_NAME'
        ];

        $default = 'MyApp';

        $stmts = GetEnvOrDefaultExp::chainOfEnvCallsWithDefault('key', $envList, $default);

        $ast = $traverser->traverse([$stmts]);
        $print = $prettyPrinter->prettyPrintFile($ast);

        $assertLine = sprintf("env('%s', env('%s', env('%s', '%s')))",
            $envList[0],
            $envList[1],
            $envList[2],
            $default);

        $this->assertStringContainsString($assertLine, $print);
    }
}
