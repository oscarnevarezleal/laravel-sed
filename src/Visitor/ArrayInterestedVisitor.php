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

namespace Laraboot\Visitor;

use PhpParser\{Node};
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;


/**
 * Class ChangeArrayValueVisitor
 * @package Laraboot\Visitor
 */
class ArrayInterestedVisitor extends NodeVisitorAbstract
{
    /**
     * @var string $searchKey
     */
    private $searchKey;
    /**
     * @var array
     */
    private $stack = [];

    /**
     * ChangeArrayValueVisitor constructor.
     */
    public function __construct(string $searchKey)
    {
        $this->searchKey = $searchKey;
        $this->stack = [];
    }

    public function beforeTraverse(array $nodes)
    {
        $this->stack = [];
    }


    /**
     * @param Node $node
     * @return void
     */
    public function enterNode(Node $node)
    {
        if (!$node instanceof ArrayItem) {
            return;
        }
        if (!$node->key instanceof String_) {
            return;
        }
        $this->stack[] = $node->key->value;
    }

    protected function clearPath()
    {
        $this->stack = [];
    }

    /**
     * @param Node $node
     * @return int|Node|Node[]|void|null
     */
    public function leaveNode(Node $node)
    {
        if (!$this->partialMatch()) {
            array_pop($this->stack);
        }
    }

    protected function matchPath(): bool
    {
        $search = $this->searchKey;

        if (count($this->stack) == 0) {
            return false;
        }

        $currentPath = implode('.', $this->stack);

        return $search === $currentPath;
    }

    protected function partialMatch(): bool
    {
        if (count($this->stack) == 0)
            return false;

        $searchKey = $this->searchKey;
        $currentPath = implode('.', $this->stack);
        $pos = stripos($searchKey, $currentPath);
        return $pos !== false;
    }
}