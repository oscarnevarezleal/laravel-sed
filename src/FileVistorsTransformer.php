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

namespace Laraboot;

use PhpParser\Parser;
use Laraboot\Schema\VisitorContext;
use PhpParser\{NodeTraverser};
use PhpParser\Node\Stmt;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

final class FileVistorsTransformer
{
    /**
     * @var mixed[]|mixed
     */
    private $visitors;
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var VisitorContext
     */
    private $visitorContext;

    /**
     * FileVistorsTransformer constructor.
     * @param array $options
     */
    public function __construct(string $filename, array $visitors, VisitorContext $visitorContext)
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        $this->filename = $filename;
        $this->visitors = $visitors;
        $this->visitorContext = $visitorContext;
    }


    public function transform(): string
    {
        $traverser = new NodeTraverser();
        $prettyPrinter = new Standard;

        foreach ($this->visitors as $visitorClass) {
            $visitor = new $visitorClass($this->visitorContext);
            $traverser->addVisitor($visitor);
        }

        $stmts = $this->parseFileContent();

        $ast = $traverser->traverse($stmts);

        return $prettyPrinter->prettyPrintFile($ast);
    }

    /**
     * @param string $filePath
     * @return string|bool
     */
    public function readFilePath()
    {
        return file_get_contents($this->filename, true);
    }

    /**
     * @return Stmt[]|null
     */
    public function parseFileContent(): array
    {
        $code = $this->readFilePath();
        return $this->parser->parse($code);
    }


    /**
     * @param $visitor
     */
    public function addVisitor($visitor): self
    {
        $this->visitors[] = $visitor;
        return $this;
    }

}