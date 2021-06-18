<?php

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
class ApplyPresetCommand extends EditCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'larased:apply-preset';

    /**
     * @return []
     */
    private function getPresets()
    {
        return [
            'cloudify' => (new Cloudify())
        ];
    }

    /**
     * @return bool
     */
    function presetExist(string $name)
    {
        return isset($this->getPresets()[$name]);
    }

    protected function configure()
    {
        $this->setDescription('Cloudify a configuration folder');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the preset to run');
    }

    /**
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
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