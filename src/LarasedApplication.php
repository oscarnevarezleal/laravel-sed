<?php

namespace Laraboot;

use Laraboot\Commands\ApplyPresetCommand;
use Laraboot\Commands\EditConfigFileCommand;
use Laraboot\Commands\GenerateConfigFile;
use Symfony\Component\Console\Application;

class LarasedApplication extends Application
{
    /**
     * LarasedApplication constructor.
     */
    public function __construct(string $name = 'Larased', string $version = '0.0.3')
    {
        parent::__construct($name, $version);

        // ... register commands
        $this->addCommands([
            new EditConfigFileCommand,
            new ApplyPresetCommand,
            new GenerateConfigFile
        ]);
    }


}