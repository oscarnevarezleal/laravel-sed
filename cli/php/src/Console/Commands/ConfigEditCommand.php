<?php

namespace Laraboot\Console\Commands;

use Laraboot\Commands\EditConfigFileCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigEditCommand extends EditConfigFileCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larased:config-edit {path} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit a configuration value and persist the result in file';

    protected function getOptions()
    {
        return $this->getDefinition()->getOptions();
    }

    protected function getArguments()
    {
        return $this->getDefinition()->getArguments();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return (int)parent::execute($input, $output);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return null;
    }
}
