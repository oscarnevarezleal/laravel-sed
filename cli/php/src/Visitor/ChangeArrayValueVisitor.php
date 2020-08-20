<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 3/24/2020
 * Time: 12:44 PM
 */

namespace LaraBoot\Visitor;

use PhpParser\{Node};


class ChangeArrayValueVisitor extends \PhpParser\NodeVisitorAbstract
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

        if ($node instanceof Node\Expr\ArrayItem) {

            if ($node->key instanceof Node\Scalar\String_ && $node->key->value === $this->options['p']) {
                // return a new Array item expression
                // we kep the same key but the value changed.
                return new Node\Expr\ArrayItem(new Node\Scalar\String_($this->options['v']), $node->key);
            }
        }
    }
}

{

}