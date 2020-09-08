<?php

namespace Laraboot\Visitor;

use Illuminate\Support\Arr;
use Laraboot\Schema\VisitorContext;
use Laraboot\Utils\HelperExpressions;
use PhpParser\{Node};
use PhpParser\Node\Expr\{Array_, ArrayItem};
use PhpParser\NodeVisitorAbstract;
use function collect;
use function get_class;
use function is_string;
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

            $items = [];
            
            foreach ($this->context->getContext() as $k => $v) {
                $key = $v['key'];
                $default = $v['value'];
                $orEnv = $v['orenv'];
                if (is_string($key)) {
                    $upperKey = strtoupper($key);
                    $items[] = new ArrayItem(
                        HelperExpressions::envOrDefault($orEnv ?? $upperKey, $default)
                        , new Node\Scalar\String_($key));
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