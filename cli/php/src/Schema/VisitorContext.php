<?php


namespace Laraboot\Schema;

use Laraboot\Schema\PathDefinition;
use Laraboot\TopLevelInputConfig;

class VisitorContext
{
    const COMMAND_KEY = TopLevelInputConfig::INPUT_COMMAND_KEY;
    const KEY_KEY = TopLevelInputConfig::OPTION_KEY_KEY;
    const VALUE_KEY = TopLevelInputConfig::INPUT_VALUE_KEY;
    const ENV_OR_KEY = TopLevelInputConfig::OPTION_ENVOR_KEY;

    const PATH_KEY = 'path';

    // PathDefinition or nulls
    private $pathDefinition;

    /**
     * @return PathDefinition
     */
    public function getPathDefinition(): PathDefinition
    {
        return $this->pathDefinition;
    }

    private array $context;

    /**
     * @param array $context
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * VisitorContext constructor.
     * @param \Laraboot\Schema\PathDefinition|null $pathDefinition
     * @param array $context
     */
    public function __construct(PathDefinition $pathDefinition = null, array $context = [])
    {
        $this->pathDefinition = $pathDefinition;
        $this->context = $context;
    }

}