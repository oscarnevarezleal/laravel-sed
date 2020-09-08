<?php

namespace Laraboot\Console\Commands;

use Laraboot\Commands\EditConfigFileCommand;

class ConfigEditCommand extends EditConfigFileCommand
{
    /**
     * The Laravel application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $laravel;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larased:config-edit';

    /**
     * The console command help text.
     *
     * @var string|null
     */
    protected $help;

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = false;

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

    /**
     * @return \Symfony\Component\Console\Input\InputOption[]
     */
    protected function getOptions()
    {
        return $this->getDefinition()->getOptions();
    }

    /**
     * @return \Symfony\Component\Console\Input\InputArgument[]
     */
    protected function getArguments()
    {
        return $this->getDefinition()->getArguments();
    }
}
