<?php

namespace Laraboot\Commands;

use Laraboot\EditCommand;
use Laraboot\Exp\EnvOrDefaultExp;
use Laraboot\TopLevelInputConfig;
use Laraboot\Utils\ConfigFileDumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function array_map;
use function array_pop;
use function array_splice;
use function count;
use function explode;
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

    /**
     * @param array|null $values
     * @return array
     */
    protected function getEnvOrDefaultExps(?array $values): array
    {
        return array_map(function ($el) {
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

}