<?php

namespace Laraboot\Exp;

use Laraboot\Utils\HelperExpressions;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use function array_pop;

final class GetEnvOrDefaultExp
{
    /**
     * Returns `[ $key => env($envName, $defaultValue) ]`
     */
    public static function asArrayItem(string $key, string $envName, string $defaultValue): ArrayItem
    {
        return new ArrayItem(HelperExpressions::envOrDefault($envName, $defaultValue), new String_($key));
    }

    /**
     * Returns `[ $key => env($envName, env($envName, env($envName, $defaultValue))) ]`
     * @param string $defaultValue
     */
    public static function chainOfEnvCallsWithDefault(string $key, array $envs, string $default): ?FuncCall
    {
        // todo
        unset($key);
        // We build inside out
        return self::getRecursion($envs, $default);
    }

    /**
     * @param string|null $key
     * @param null $carry
     * @return FuncCall|null|void
     */
    private static function getRecursion(array $envs, string $default, string $key = null, $carry = null)
    {
        $factory = new BuilderFactory();
        if (!$carry) {
            $recursion = null;
            while ($current = array_pop($envs)) {
                $recursion = $recursion !== null ? self::getRecursion($envs, $default, $current, $recursion) : $factory->funcCall('env', [$current, $default]);
            }
            return $recursion;
        }
        return $factory->funcCall('env', [$key, $carry]);
    }
}