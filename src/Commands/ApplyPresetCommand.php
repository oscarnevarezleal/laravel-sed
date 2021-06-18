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
use Laraboot\Presets\Cloudify;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function array_keys;
use function sprintf;

/**
 * Class EditConfigFileCommand
 * @package Laraboot\Commands
 */
final class ApplyPresetCommand extends EditCommand
{
    // the name of the command (the part after "bin/console")
    /**
     * @var string
     */
    protected static $defaultName = 'larased:apply-preset';

    /**
     * @return []
     */
    private function getPresets(): array
    {
        return [
            'cloudify' => (new Cloudify())
        ];
    }

    function presetExist(string $name): bool
    {
        return isset($this->getPresets()[$name]);
    }

    protected function configure(): void
    {
        $this->setDescription('Cloudify a configuration folder');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the preset to run');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $presetName = $input->getArgument('name');

        if (!$this->presetExist($presetName)) {
            $suggestions = implode(', ', array_keys($this->getPresets()));
            $output->writeln(sprintf('Not such preset registered. Try %s', $suggestions));
            return 255;
        }

        $context = $this->getVisitorContext($input);

        $output->writeln(sprintf('Using preset %s', $presetName));

        /**
         * @var $preset
         */
        $preset = $this->getPreset($presetName);
        $preset->setContext($context);
        $preset->execute();

        return Command::SUCCESS;
    }

    private function getPreset(string $presetName)
    {
        $className = $this->getPresets()[$presetName];
        return new $className;
    }

}