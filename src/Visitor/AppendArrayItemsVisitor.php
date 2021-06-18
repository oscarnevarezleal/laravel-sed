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

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use Laraboot\Exp\EnvOrDefaultExp;
use Laraboot\Exp\GetEnvOrDefaultExp;
use Laraboot\Schema\VisitorContext;
use PhpParser\{Node};
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;


/**
 * Class ChangeArrayValueVisitor
 * @package Laraboot\Visitor
 */
final class AppendArrayItemsVisitor extends NodeVisitorAbstract
{
    /**
     * @var VisitorContext $context
     */
    private $context;

    /**
     * AppendArrayItemsVisitor constructor.
     */
    public function __construct(VisitorContext $context)
    {
        $this->context = $context;
    }

    public function fromArray(array $options): \Laraboot\Visitor\AppendArrayItemsVisitor
    {
        return new self(VisitorContext::fromArray($options));
    }

    /**
     * @param Node $node
     * @return Node
     */
    public function leaveNode(Node $node): Node
    {
        if ($node instanceof Array_) {

            $items = $node->items ?? [];

            foreach ($this->context->getContext() as $v) {

                if ($v instanceof EnvOrDefaultExp) {
                    $attrs = $v->getAttributes();
                    $items[] = new ArrayItem(
                        GetEnvOrDefaultExp::chainOfEnvCallsWithDefault($attrs['key'], $attrs['orenv'], $attrs['value'])
                        , new String_($attrs['key']));
                }

            }

            $node->items = $items;
            return $node;

        }
        return $node;
    }
}
