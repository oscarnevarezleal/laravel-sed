<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 3/24/2020
 * Time: 12:44 PM
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
class AppendArrayValueVisitor extends NodeVisitorAbstract
{
    private $options;
    private $builder;

    /**
     * AppendArrayValueVisitor constructor.
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