<?php


namespace Laraboot\Schema;

use Laraboot\TopLevelInputConfig;

class VisitorContext
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
    private $pathDefinition;

    public function getPathDefinition(): PathDefinition
    {
        return $this->pathDefinition;
    }

    private $context;

    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * VisitorContext constructor.
     * @param PathDefinition|null $pathDefinition
     */
    public function __construct(PathDefinition $pathDefinition = null, array $context = [])
    {
        $this->pathDefinition = $pathDefinition;
        $this->context = $context;
    }

    /**
     * @return VisitorContext
     */
    public static function fromArray(array $a = [])
    {
        return new self(null, $a);
    }

}