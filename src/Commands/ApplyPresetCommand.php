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