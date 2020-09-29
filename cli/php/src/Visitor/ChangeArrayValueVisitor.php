<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 3/24/2020
 * Time: 12:44 PM
 */

namespace Laraboot\Visitor;

use Laraboot\Schema\VisitorContext;
use Laraboot\Utils\HelperExpressions;
use PhpParser\{Node};
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;


/**
 * Class ChangeArrayValueVisitor
 * @package Laraboot\Visitor
 */
class ChangeArrayValueVisitor extends NodeVisitorAbstract
{
    /**
     * @var VisitorContext $VisitorContext
     */
    private $VisitorContext;

    /**
     * ChangeArrayValueVisitor constructor.
     * @param VisitorContext $VisitorContext
     */
    public function __construct(VisitorContext $VisitorContext)
    {
        $this->VisitorContext = $VisitorContext;
    }

    /**
     * @param array $options
     * @return AppendArrayItemsVisitor
     */
    public function fromArray(array $options): self
    {
        return new self(VisitorContext::fromArray($options));
    }


    /**
     * @param Node $node
     * @return Node
     */
    public function leaveNode(Node $node): Node
    {
        $context = $this->VisitorContext->getContext();
        $searchKey = $context[VisitorContext::PATH_KEY];
        $replaceValue = $context[VisitorContext::VALUE_KEY];

        if ($node instanceof ArrayItem) {

            if ($node->key instanceof String_ && $node->key->value === $searchKey) {

                if (isset($context[VisitorContext::ENV_OR_KEY])) {
                    // Return a function call expression
                    // In the form of '$key' => env($env, $default);
                    list($env, $default) = explode('|', $context[VisitorContext::ENV_OR_KEY]);
                    HelperExpressions::envOrDefault($env, $default);
                    return new ArrayItem(HelperExpressions::envOrDefault($env, $default), $node->key);
                } else {
                    // return a new Array item expression
                    // we kep the same key but the value changed.
                    return new ArrayItem(new String_($replaceValue), $node->key);
                }
            } else {
                return $node;
            }
        }

        return $node;
    }
}