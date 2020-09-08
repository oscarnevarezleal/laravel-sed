#!/usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Laraboot\Commands\EditConfigFileCommand;
use Laraboot\Commands\ApplyPresetCommand;
use Laraboot\Commands\GenerateConfigFile;
use Laraboot\LarasedApplication;

$application = new LarasedApplication();

// ... register commands
$application->addCommands([
    new EditConfigFileCommand,
    new ApplyPresetCommand,
    new GenerateConfigFile
]);

$application->run();

