<?php


namespace Laraboot\Schema;

use Laraboot\TopLevelInputConfig;
use function array_pop;
use function array_walk;
use function explode;

class PathDefinition
{
    const PATH_SEPARATOR = '/';

    const PROPERTY_FILENAME = 'propertyPath';

    const PROPERTY_KEY = TopLevelInputConfig::INPUT_PATH_KEY;
    const PROPERTY_PATH = TopLevelInputConfig::INPUT_PATH_KEY;

    private $fileName;

    /**
     * @return mixed
     */
    public function getFileName()
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

    /**
     * @param string $str
     * @return PathDefinition
     */
    public static function fromString(string $str): PathDefinition
    {
        return new self(\explode(self::PATH_SEPARATOR, $str));
    }

    /**
     * @param array $tokens
     * @return PathDefinition
     */
    public static function fromArray(array $tokens): PathDefinition
    {
        return new self([
            $tokens[self::PROPERTY_PATH],
            $tokens[self::PROPERTY_FILENAME]
        ]);
    }

    /**
     * PathDefinition constructor.
     * @param array $pieces
     */
    public function __construct(array $pieces)
    {
        list($path, $key) = $pieces;

        $this->fileName = $this->getFullPath($path);
        $this->key = $this->extractKey($key);
        $this->propertyPath = $key;
    }

    /**
     * @return array
     *
     */
    public function asArray()
    {
        return [
            self::PROPERTY_FILENAME => $this->getFileName(),
            self::PROPERTY_PATH => $this->getPropertyPath(),
            self::PROPERTY_KEY => $this->getKey()
        ];
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getFullPath($path)
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