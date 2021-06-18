<?php
/*
 * Copyright (c) 2021. Oscar Nevarez Leal <fu.wire@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

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