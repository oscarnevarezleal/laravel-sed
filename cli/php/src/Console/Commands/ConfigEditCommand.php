<?php

namespace Laraboot\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ConfigEditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:edit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit a configuration value and persist the result in file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $binPath = base_path() . '/vendor/oscarnevarezleal/laravel-sed/cli/php/main.php';
        $commandStr = sprintf('php %s -a config.edit -p name -e APP_NAME|Changed -d %s',
            $binPath,
            base_path());
//        echo $commandStr;
        $command = explode(' ', $commandStr);
        $process = new Process($command);
        $process->run();
        echo $process->getOutput();

        return $process->getExitCode();
    }
}
