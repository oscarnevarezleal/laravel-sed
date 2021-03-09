<?php

namespace Laraboot;

use Symfony\Component\Console\Application;
use Laraboot\Commands\EditConfigFileCommand;
use Laraboot\Commands\ApplyPresetCommand;
use Laraboot\Commands\GenerateConfigFile;

class LarasedApplication extends Application
{
    /**
     * LarasedApplication constructor.
     * @param string $name
     * @param string $version
     */
    public function __construct(string $name = 'Larased', string $version = '0.0.1')
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