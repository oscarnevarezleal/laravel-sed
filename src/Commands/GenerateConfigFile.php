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