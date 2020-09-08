<?php

namespace Laraboot\Exp;

use Laraboot\Utils\HelperExpressions;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\{ArrayItem};
use PhpParser\Node\Scalar\{String_};
use function array_pop;

class GetEnvOrDefaultExp
{

    /**
     * Returns `[ $key => env($envName, $defaultValue) ]`
     * @param string $key
     * @param string $envName
     * @param string $defaultValue
     * @return ArrayItem
     */
    public static function asArrayItem(string $key, string $envName, string $defaultValue): ArrayItem
    {
        return new ArrayItem(
            HelperExpressions::envOrDefault($envName, $defaultValue)
            , new String_($key));
    }

    /**
     * Returns `[ $key => env($envName, env($envName, env($envName, $defaultValue))) ]`
     * @param string $key
     * @param array $envs
     * @param string $defaultValue
     */
    public static function chainOfEnvCallsWithDefault(string $key, array $envs, string $defaultValue)
    {
        // We build inside out
        return self::getRecursion($envs);
    }

    /**
     * @param array $envs
     * @param string|null $key
     * @param null $carry
     * @return \PhpParser\Node\Expr\FuncCall|null
     */
    private static function getRecursion(array $envs, string $key = null, $carry = null)
    {

        $factory = new BuilderFactory();

        if (!$carry) {

            $current = null;
            $recursion = null;

            while ($current = array_pop($envs)) {

                $recursion = $recursion ?
                    self::getRecursion($envs, $current, $recursion) :
                    $factory->funcCall('env', [$current]);

            }

            return $recursion;

        } else {
            return $factory->funcCall('env', [$key, $carry]);
        }
    }
}