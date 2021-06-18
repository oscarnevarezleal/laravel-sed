<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 3/24/2020
 * Time: 12:44 PM
 */

namespace Laraboot\Visitor;

use PhpParser\{Node};
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;


/**
 * Class ChangeArrayValueVisitor
 * @package Laraboot\Visitor
 */
class ArrayInterestedVisitor extends NodeVisitorAbstract
{
    /**
     * @var string $searchKey
     */
    private $searchKey;
    /**
     * @var array
     */
    private $stack = [];

    /**
     * ChangeArrayValueVisitor constructor.
     */
    public function __construct(string $searchKey)
    {
        $this->searchKey = $searchKey;
        $this->stack = [];
    }

    public function beforeTraverse(array $nodes)
    {
        $this->stack = [];
    }


    /**
     * @param Node $node
     * @return void
     */
    public function enterNode(Node $node)
    {
        if (!$node instanceof ArrayItem) {
            return;
        }
        if (!$node->key instanceof String_) {
            return;
        }
        $this->stack[] = $node->key->value;
    }

    protected function clearPath()
    {
        $this->stack = [];
    }

    /**
     * @param Node $node
     * @return int|Node|Node[]|void|null
     */
    public function leaveNode(Node $node)
    {
        if (!$this->partialMatch()) {
            array_pop($this->stack);
        }
    }

    protected function matchPath(): bool
    {
        $search = $this->searchKey;

        if (count($this->stack) == 0) {
            return false;
        }

        $currentPath = implode('.', $this->stack);

        return $search === $currentPath;
    }

    protected function partialMatch(): bool
    {
        if (count($this->stack) == 0)
            return false;

        $searchKey = $this->searchKey;
        $currentPath = implode('.', $this->stack);
        $pos = stripos($searchKey, $currentPath);
        return $pos !== false;
    }
}