<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 3/24/2020
 * Time: 12:44 PM
 */

namespace Laraboot\Visitor;

use Laraboot\Exp\GetEnvOrDefaultExp;
use Laraboot\Schema\VisitorContext;
use Laraboot\Utils\HelperExpressions;
use PhpParser\{Node as NodeAlias};
use PhpParser\Node\Expr\ArrayItem;


/**
 * Class ChangeArrayValueVisitor
 * @package Laraboot\Visitor
 */
class ChangeNestedArrayValueVisitor extends ArrayInterestedVisitor
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
                    $tokens = explode('|', $context[VisitorContext::ENV_OR_KEY]);
                    $keyValue = $node->key->value;
                    // Two options , simple env(K, value) or env(env(env(..., default)))
                    return new ArrayItem(
                        GetEnvOrDefaultExp::chainOfEnvCallsWithDefault($keyValue, $tokens, $replaceValue)
                        , new NodeAlias\Scalar\String_($keyValue));

                } else {
                    return new ArrayItem(HelperExpressions::envOrDefault($context[VisitorContext::ENV_OR_KEY]
                        , $context[VisitorContext::VALUE_KEY]), $node->key);
                }

            } else {
                // return a new Array item expression
                // we kep the same key but the value changed.
                return new ArrayItem(new NodeAlias\Scalar\String_($replaceValue), $node->key);
            }
        } else {
            return $node;
        }
    }

}