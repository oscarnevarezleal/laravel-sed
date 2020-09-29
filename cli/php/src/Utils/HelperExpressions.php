<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 8/20/2020
 * Time: 2:44 PM
 */

namespace Laraboot\Utils;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\{BuilderFactory, Node};


class HelperExpressions
{

    /**
     * @param string $env
     * @param string $default
     * @return Node\Expr\FuncCall
     */
    public static function envOrDefault(string $env, string $default): FuncCall
    {
        $factory = new BuilderFactory();
        return $factory->funcCall('env', [$env, $default]);
    }
}