<?php

namespace Laraboot\Commands;

use Laraboot\EditCommand;
use Laraboot\TopLevelInputConfig;
use Laraboot\Utils\ConfigFileDumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EditConfigFileCommand
 * @package Laraboot\Commands
 */
final class GenerateConfigFile extends EditCommand
{
    // the name of the command (the part after "bin/console")
    /**
     * @var string
     */
    protected static $defaultName = 'larased:config-gen';

    protected function configure(): void
    {
        $this->setDescription('Config file generator');
        $this->addArgument(TopLevelInputConfig::INPUT_FILENAME_KEY, InputArgument::REQUIRED, 'The name of the config file');
        $this->addOption(TopLevelInputConfig::OPTION_VALUES_KEY, 'o'
            , InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY
            , '');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $context = $this->getVisitorContext($input);
        $values = $input->getOption('values');

        $kValues = $this->getEnvOrDefaultExps($values);

        $context->setContext($kValues);

        /**
         * @var $preset
         */
        $preset = new ConfigFileDumper($context);
        $preset->setContext($context);

        $outputCode = $preset->execute();

        echo $outputCode;

        return Command::SUCCESS;
    }

}