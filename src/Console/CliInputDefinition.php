<?php


namespace Laraboot\Console;

use Laraboot\TopLevelInputConfig;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

final class CliInputDefinition extends InputDefinition
{
    /**
     * @return mixed[]
     */
    private static function defaultDefinition(): array
    {
        //
        /** @var array $options */
        $options = [
            new InputArgument(TopLevelInputConfig::INPUT_PATH_KEY, InputArgument::REQUIRED, ''),
            new InputArgument(TopLevelInputConfig::INPUT_VALUE_KEY, InputArgument::REQUIRED, ''),
            new InputOption(TopLevelInputConfig::OPTION_BASEPATH_KEY, 'd', InputOption::VALUE_REQUIRED, ''),
            new InputOption(TopLevelInputConfig::OPTION_ENVOR_KEY, 'e', InputOption::VALUE_REQUIRED, '')
        ];

        return $options;
    }

    /**
     * CliInputDefinition constructor.
     * @param array|null $definition
     */
    public function __construct(array $definition = null)
    {
        if (!$definition) {
            $definition = self::defaultDefinition();
        }

        parent::__construct($definition);
    }
}