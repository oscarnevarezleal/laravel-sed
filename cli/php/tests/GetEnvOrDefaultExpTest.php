<?php

use Laraboot\Exp\GetEnvOrDefaultExp;
use PhpParser\{NodeTraverser, PrettyPrinter};
use PHPUnit\Framework\TestCase;

class GetEnvOrDefaultExpTest extends TestCase
{
    public function testGetEnvOrDefaultExp()
    {
        $envList = [
            'ORGANIZATION_NAME',
            'PROJECT_NAME',
            'APP_NAME'
        ];

        $stmts = GetEnvOrDefaultExp::chainOfEnvCallsWithDefault('key', $envList, 'MyApp');

        $traverser = new NodeTraverser;
        $prettyPrinter = new PrettyPrinter\Standard;

        $ast = $traverser->traverse([$stmts]);

        $print = $prettyPrinter->prettyPrintFile($ast);

        echo $print;
    }
}
