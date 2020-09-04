<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 3/24/2020
 * Time: 12:44 PM
 */

namespace Laraboot\Visitor;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Laraboot\HelperExpressions;
use PhpParser\{Node};

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

            if ($node->key instanceof String_ && $node->key->value === $this->options['p']) {

                if (array_key_exists('e', $this->options)) {
                    // Return a function call expression
                    // In the form of '$key' => env($env, $default);
                    list($env, $default) = explode('|', $this->options['e']);
                    HelperExpressions::envOrDefault($env, $default);
                    return new ArrayItem(HelperExpressions::envOrDefault($env, $default), $node->key);
                } else {
                    // return a new Array item expression
                    // we kep the same key but the value changed.
                    return new ArrayItem(new String_($this->options['v']), $node->key);
                }
            } else {
                return $node;
            }
        }
    }
}