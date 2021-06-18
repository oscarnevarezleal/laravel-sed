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


namespace Laraboot\Schema;

use Laraboot\TopLevelInputConfig;

final class VisitorContext
{
    /**
     * @var string
     */
    const COMMAND_KEY = TopLevelInputConfig::INPUT_COMMAND_KEY;
    /**
     * @var string
     */
    const KEY_KEY = TopLevelInputConfig::OPTION_KEY_KEY;
    /**
     * @var string
     */
    const VALUE_KEY = TopLevelInputConfig::INPUT_VALUE_KEY;
    /**
     * @var string
     */
    const ENV_OR_KEY = TopLevelInputConfig::OPTION_ENVOR_KEY;

    /**
     * @var string
     */
    const MODE = 'mode';
    /**
     * @var string
     */
    const PATH_KEY = 'path';

    // PathDefinition or nulls
    /**
     * @var PathDefinition|null
     */
    private $pathDefinition;

    public function getPathDefinition(): PathDefinition
    {
        return $this->pathDefinition;
    }

    /**
     * @var mixed[]|mixed
     */
    private $context;

    /**
     * @param mixed[] $context
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    /**
     * @return mixed[]
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * VisitorContext constructor.
     * @param PathDefinition|null $pathDefinition
     * @param mixed[] $context
     */
    public function __construct(PathDefinition $pathDefinition = null, array $context = [])
    {
        $this->pathDefinition = $pathDefinition;
        $this->context = $context;
    }

    public static function fromArray(array $a = []): \Laraboot\Schema\VisitorContext
    {
        return new self(null, $a);
    }

}