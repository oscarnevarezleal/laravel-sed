<?php

namespace Laraboot\Exp;

use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;

final class EnvOrDefaultExp extends Stmt
{
    public function getSubNodeNames(): array
    {
        return ['name', 'value', 'byRef', 'unpack'];
    }

    public function getType(): string
    {
        return 'EnvOrDefaultExp';
    }

}