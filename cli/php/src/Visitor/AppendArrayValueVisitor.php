<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 3/24/2020
 * Time: 12:44 PM
 */

namespace Laraboot\Visitor;

use PhpParser\{Node};
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\{Array_, ArrayItem};
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;

/**
 * Class AppendArrayValueVisitor
 * @package Laraboot\Visitor
 */
class AppendArrayValueVisitor extends NodeVisitorAbstract
{
    private $options;

    /**
     * ChangeArrayValueVisitor constructor.
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param Node $node
     * @return int|Node|Node[]|void|null
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof ArrayItem) {
            /**
             * @var $node ArrayItem
             */
            if ($node->key instanceof String_ && $node->key->value === $this->options['p']) {
                if ($node->value instanceof Array_ && $node->value->items) {
                    $builder = new BuilderFactory();
                    $newItem = new ArrayItem($builder->classConstFetch($this->options['v'], 'class'));
                    $node->value->items[] = $newItem;
                }
            }
        }
    }
}