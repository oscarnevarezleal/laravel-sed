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

use Expr\{Array_, ArrayItem};
use PhpParser\{Node};
use PhpParser\BuilderFactory;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;

/**
 * Class AppendArrayValueVisitor
 * @package Laraboot\Visitor
 */
final class AppendArrayValueVisitor extends NodeVisitorAbstract
{
    /**
     * @var mixed[]
     */
    private $options = [];
    /**
     * @var BuilderFactory
     */
    private $builder;

    /**
     * AppendArrayValueVisitor constructor.
     * @param mixed[] $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
        $this->builder = new BuilderFactory();
    }

    /**t
     * @param Node $node
     * @return int|Node|Node[]|void|null
     */
    public function leaveNode(Node $node)
    {
        /**
         * @var $node ArrayItem
         */
        if ($node instanceof ArrayItem && $this->hasKeyName($node, $this->options['p']) && ($node->value instanceof Array_ && $node->value->items)) {
            $newItem = new ArrayItem($this->builder->classConstFetch($this->options['v'], 'class'));
            $node->value->items[] = $newItem;
        }
    }

    /**
     * @param ArrayItem $arrayItem
     */
    private function hasKeyName(ArrayItem $arrayItem, string $name): bool
    {
        return $arrayItem->key instanceof String_ && $arrayItem->key->value === $name;
    }
}