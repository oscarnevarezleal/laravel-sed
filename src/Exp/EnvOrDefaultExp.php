<?php

namespace Laraboot\Exp;

use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;

class EnvOrDefaultExp extends Stmt
{
    /** @var Identifier|null Parameter name (for named parameters) */
    public $name;
    /** @var Expr Value to pass */
    public $value;
    /** @var bool Whether to pass by ref */
    public $byRef;
    /** @var bool Whether to unpack the argument */
    public $unpack;

    public function getSubNodeNames(): array
    {
        return ['name', 'value', 'byRef', 'unpack'];
    }

    public function getType(): string
    {
        return 'EnvOrDefaultExp';
    }

}