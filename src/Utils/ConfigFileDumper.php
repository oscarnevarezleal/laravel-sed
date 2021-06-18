<?php
/*
 * Copyright (c) 2021. Oscar Nevarez Leal <fu.wire@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */


namespace Laraboot\Utils;

use PhpParser\Parser;
use Laraboot\Schema\VisitorContext;
use Laraboot\Visitor\AppendArrayItemsVisitor;
use PhpParser\{NodeTraverser};
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

final class ConfigFileDumper
{
    /**
     * @var Parser
     */
    private $parser;
    /**
     * @var VisitorContext $context
     */
    private $context;
    /**
     * @var string
     */
    private const BOILER_PLATE = '<?php return []; ';

    /**
     * ConfigFileDumper constructor.
     */
    public function __construct(VisitorContext $context = null)
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        if ($context !== null) {
            $this->context = $context;
        }
    }

    public function getContext(): VisitorContext
    {
        return $this->context;
    }

    public function setContext(VisitorContext $context): void
    {
        $this->context = $context;
    }


    public function execute(): string
    {
        $traverser = new NodeTraverser();
        $prettyPrinter = new Standard;

        foreach ($this->getVisitors() as $visitorClass) {
            $visitor = new $visitorClass($this->context);
            $traverser->addVisitor($visitor);
        }

        $stmts = $this->parser->parse(self::BOILER_PLATE);

        $ast = $traverser->traverse($stmts);

        return $prettyPrinter->prettyPrintFile($ast);
    }

    /**
     * @return array<class-string<AppendArrayItemsVisitor>>
     */
    protected function getVisitors(): array
    {
        return [
            AppendArrayItemsVisitor::class
        ];
    }


}