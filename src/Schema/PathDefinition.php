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
use function array_pop;
use function array_walk;
use function explode;

final class PathDefinition
{
    /**
     * @var string
     */
    const PATH_SEPARATOR = '/';

    /**
     * @var string
     */
    const PROPERTY_FILENAME = 'propertyPath';

    /**
     * @var string
     */
    const PROPERTY_KEY = TopLevelInputConfig::INPUT_PATH_KEY;
    /**
     * @var string
     */
    const PROPERTY_PATH = TopLevelInputConfig::INPUT_PATH_KEY;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @return mixed
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    private $propertyPath;

    /**
     * @return mixed
     */
    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    private $key;

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    public static function fromString(string $str): PathDefinition
    {
        return new self(\explode(self::PATH_SEPARATOR, $str));
    }

    public static function fromArray(array $tokens): PathDefinition
    {
        return new self([
            $tokens[self::PROPERTY_PATH],
            $tokens[self::PROPERTY_FILENAME]
        ]);
    }

    /**
     * PathDefinition constructor.
     */
    public function __construct(array $pieces)
    {
        list($path, $key) = $pieces;

        $this->fileName = $this->getFullPath($path);
        $this->key = $this->extractKey($key);
        $this->propertyPath = $key;
    }

    /**
     * @return array<string, mixed>
     */
    public function asArray(): array
    {
        return [
            self::PROPERTY_FILENAME => $this->fileName,
            self::PROPERTY_PATH => $this->propertyPath,
            self::PROPERTY_KEY => $this->key
        ];
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getFullPath($path): string
    {
        $tokens = explode('.', $path);
        array_walk($tokens, function ($token) {
            return $token;
        });
        return implode('/', $tokens);
    }

    /**
     * Given a path structure where the dot is the separator, the key would be the last element of that list
     * @param $path
     * @return mixed|string
     */
    private function extractKey($path)
    {
        $tokens = explode('.', $path);
//        print_r($tokens);
        return count($tokens) == 1 ? array_pop($tokens) : $path;
    }
}