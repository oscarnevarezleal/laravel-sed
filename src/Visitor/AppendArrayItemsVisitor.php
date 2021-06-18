<?php

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
