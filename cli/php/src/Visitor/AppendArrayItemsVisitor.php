<?php

namespace Laraboot\Visitor;

use Laraboot\Exp\EnvOrDefaultExp;
use Laraboot\Exp\GetEnvOrDefaultExp;
use Laraboot\Schema\VisitorContext;
use Laraboot\Utils\HelperExpressions;
use PhpParser\{Node};
use PhpParser\Node\Expr\{Array_, ArrayItem};
use PhpParser\NodeVisitorAbstract;
use function get_class;
use function print_r;
use function strtoupper;


/**
 * Class ChangeArrayValueVisitor
 * @package Laraboot\Visitor
 */
class AppendArrayItemsVisitor extends NodeVisitorAbstract
{
    private VisitorContext $context;

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
     * @return int|Node|Node[]|void|null
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Array_) {

            $items = $node->items ?? [];

            foreach ($this->context->getContext() as $k => $v) {

                if ($v instanceof EnvOrDefaultExp) {
                    $attrs = $v->getAttributes();
                    $items[] = new ArrayItem(
                        GetEnvOrDefaultExp::chainOfEnvCallsWithDefault($attrs['key'], $attrs['orenv'], $attrs['value'])
                        , new Node\Scalar\String_($attrs['key']));
                }

            }

            $node->items = $items;
            return $node;

        } else {
            return $node;
        }
    }
}
