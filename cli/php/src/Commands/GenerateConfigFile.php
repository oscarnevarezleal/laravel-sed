<?php

namespace Laraboot\Commands;

use Laraboot\EditCommand;
use Laraboot\Presets\Cloudify;
use Laraboot\TopLevelInputConfig;
use Laraboot\Utils\ConfigFileDumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function array_keys;
use function array_map;
use function explode;
use function print_r;
use function sprintf;
use function stripos;

/**
 * Class EditConfigFileCommand
 * @package Laraboot\Commands
 */
class GenerateConfigFile extends EditCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'larased:config-gen';

    protected function configure()
    {
        $this->setDescription('Config file generator');
        $this->addArgument(TopLevelInputConfig::INPUT_FILENAME_KEY, InputArgument::REQUIRED, 'The name of the config file');
        $this->addOption(TopLevelInputConfig::OPTION_VALUES_KEY, 'o'
            , InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY
            , '');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $context = $this->getVisitorContext($input);
        $values = $input->getOption('values');

        $kValues = array_map(function ($el) {
            $orEnv = null;
            list($key, $value) = explode('=', $el);
            if (stripos($value, '|') !== FALSE) {
                $sub = explode('|', $value);
                $orEnv = $sub[0];
                $value = $sub[1];
            }
            return ['key' => $key, 'value' => $value, 'orenv' => $orEnv];
        }, $values);

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