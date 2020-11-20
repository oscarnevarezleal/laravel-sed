<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 11/19/2020
 * Time: 1:06 PM
 */

use Laraboot\Schema\VisitorContext;
use Laraboot\Visitor\ChangeNestedArrayValueVisitor;
use PhpParser\{NodeTraverser, PrettyPrinter};
use PHPUnit\Framework\TestCase;

class ChangeNestedArrayValueVisitorTest extends TestCase
{
    private $parser;

    /**
     * Assert that given a list of environment variables names,
     * the result expression contains a nested call to _env_ function,
     * having the deepest call a default value.
     * E.g. env('ORGANIZATION_NAME', env('PROJECT_NAME', env('APP_NAME', 'MyApp')))
     * @dataProvider scenariosProvider
     */
    public function testChangeNestedPathExpression(VisitorContext $visitorContext, string $assertion)
    {
        $traverser = new NodeTraverser;
        $prettyPrinter = new PrettyPrinter\Standard;

        $code = "<?php
            return [
                'default' => 'b',
                
                'a' => [
                    'a_x' => [
                        'a_y' => 0
                    ]
                ],
                'b' => [
                    'b_x' => [
                        'b_y' => 0
                    ]
                ] 
           ];
        ";

        $this->parser = (new \PhpParser\ParserFactory())
            ->create(\PhpParser\ParserFactory::PREFER_PHP7);

        $traverser->addVisitor(new ChangeNestedArrayValueVisitor($visitorContext));

        $prettyPrinter = new PrettyPrinter\Standard();

        $stmts = $this->parser->parse($code);

        $ast = $traverser->traverse($stmts);

        $pretty = $prettyPrinter->prettyPrintFile($ast);

        $this->assertStringContainsString($assertion, $pretty);
    }

    /**
     * @return array
     */
    public function scenariosProvider(): array
    {
        return [
            [
                VisitorContext::fromArray([
                    VisitorContext::PATH_KEY => 'b.b_x.b_y',
                    VisitorContext::VALUE_KEY => 'happy'
                ]), "'b' => ['b_x' => ['b_y' => 'happy']]]"
            ],
            [
                VisitorContext::fromArray([
                    VisitorContext::PATH_KEY => 'b.b_x.b_y',
                    VisitorContext::ENV_OR_KEY => 'DEFAULT_B',
                    VisitorContext::VALUE_KEY => 'happy'
                ]), "'b' => ['b_x' => ['b_y' => env('DEFAULT_B', 'happy')]]]"
            ]
        ];
    }
}