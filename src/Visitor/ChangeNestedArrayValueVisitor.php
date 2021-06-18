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

use Laraboot\Schema\VisitorContext;
use Laraboot\Utils\HelperExpressions;
use PhpParser\{Node as NodeAlias};
use PhpParser\Node\Expr\ArrayItem;


/**
 * Class ChangeArrayValueVisitor
 * @package Laraboot\Visitor
 */
final class ChangeNestedArrayValueVisitor extends ArrayInterestedVisitor
{
    /**
     * @var VisitorContext $visitorContext
     */
    private $visitorContext;

    /**
     * ChangeArrayValueVisitor constructor.
     */
    public function __construct(VisitorContext $context)
    {
        $contextArray = $context->getContext();
        $searchKey = $contextArray[VisitorContext::PATH_KEY];
        parent::__construct($searchKey);

        $this->visitorContext = $context;
    }

    public function fromArray(array $options): self
    {
        return new self(VisitorContext::fromArray($options));
    }


    /**
     * @param NodeAlias $node
     * @return NodeAlias
     */
    public function leaveNode(NodeAlias $node): NodeAlias
    {
        parent::leaveNode($node);

        $context = $this->visitorContext->getContext();
        $replaceValue = $context[VisitorContext::VALUE_KEY];

        if ($node instanceof ArrayItem && $this->matchPath()) {
            // clear the found path
            $this->clearPath();
            if (isset($context[VisitorContext::ENV_OR_KEY])) {
                // Return a function call expression
                if (stripos($context[VisitorContext::ENV_OR_KEY], '|') !== false) {
                    // In the form of '$key' => env($env, $default);
                    list($env, $default) = explode('|', $context[VisitorContext::ENV_OR_KEY]);
                    return new ArrayItem(HelperExpressions::envOrDefault($env, $default), $node->key);
                }
                return new ArrayItem(HelperExpressions::envOrDefault($context[VisitorContext::ENV_OR_KEY]
                    , $context[VisitorContext::VALUE_KEY]), $node->key);

            }
            // return a new Array item expression
            // we kep the same key but the value changed.
            return new ArrayItem(new NodeAlias\Scalar\String_($replaceValue), $node->key);
        } else {
            return $node;
        }
    }

}