<?php


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