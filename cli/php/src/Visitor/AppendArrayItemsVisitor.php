<?php

namespace Laraboot\Visitor;

use Laraboot\Exp\EnvOrDefaultExp;
use Laraboot\Exp\GetEnvOrDefaultExp;
use Laraboot\Schema\VisitorContext;
use PhpParser\{Node};
use PhpParser\Node\Expr\{Array_, ArrayItem};
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;


/**
 * Class ChangeArrayValueVisitor
 * @package Laraboot\Visitor
 */
class AppendArrayItemsVisitor extends NodeVisitorAbstract
{
    /**
     * @var VisitorContext $context
     */
    private $context;

    /**
     * AppendArrayItemsVisitor constructor.
     * @param VisitorContext $context
     */
    public function __construct(VisitorContext $context)
    {
        $this->context = $context;
    }

    /**
     * @param array $options
     * @return AppendArrayItemsVisitor
     */
    public function fromArray(array $options)
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

            $items = [];

            foreach ($this->context->getContext() as $k => $v) {

                if ($v instanceof EnvOrDefaultExp) {
                    $attrs = $v->getAttributes();
                    $items[] = new ArrayItem(
                        GetEnvOrDefaultExp::chainOfEnvCallsWithDefault($attrs['key'], $attrs['orenv'], $attrs['value'])
                        , new String_($attrs['key']));
                }

            }
            /**
             * @var $node Array_
             */
            return new Array_($items);

        } else {
            return $node;
        }
    }
}