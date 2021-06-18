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

namespace Laraboot;

use Laraboot\Exp\EnvOrDefaultExp;
use Laraboot\Schema\PathDefinition;
use Laraboot\Schema\VisitorContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use function array_merge;
use function dirname;
use function function_exists;

class EditCommand extends Command
{

    /**
     * Splits the path into tokens and initialize a PathDefinition with those tokens
     * @param string|null $path
     */
    protected function getPathDefinition(string $path): PathDefinition
    {
        return PathDefinition::fromString($path);
    }

    /**
     * @param PathDefinition|null $pathDef
     */
    protected function getVisitorContext(InputInterface $input
        , PathDefinition $pathDef = null): VisitorContext
    {
        $inputContext = array_merge($input->getArguments(), $input->getOptions());

        // e.g config.hashing/bcrypt.rounds
        $rightSide = explode('/', $inputContext[VisitorContext::PATH_KEY])[1];

        $mode = substr_count($rightSide, '.') > 0 ? 'nested' : 'default';

        $inputContext[VisitorContext::MODE] = $mode;

        if ($pathDef !== null) {
            $inputContext = array_merge($inputContext, $pathDef->asArray());
        }

        return new VisitorContext($pathDef, $inputContext);
    }

    /**
     * @return EnvOrDefaultExp[]
     */
    protected function getEnvOrDefaultExps(?array $values): array
    {
        return array_map(function ($el): EnvOrDefaultExp {
            $orEnv = null;
            list($key, $value) = explode('=', $el);
//            echo $value . "\n";
            if (stripos($value, '|') !== FALSE) {
                $sub = explode('|', $value);
                $count = count($sub);
                if ($count > 1) {
                    // we have a list of envs
                    $orEnv = array_splice($sub, 0, $count - 1);
//                    print_r($orEnv);
                    $value = array_pop($sub);
//                    print_r($value);
                } else {
                    $orEnv = $sub[0];
                    $value = $sub[1];
                }
            }
            return new EnvOrDefaultExp([
                'key' => $key,
                'value' => $value,
                'orenv' => $orEnv
            ]);
        }, $values);
    }

    public function getAppDirectory(): string
    {
        return function_exists('base_path') ?
            base_path() : dirname(__DIR__);
    }
}