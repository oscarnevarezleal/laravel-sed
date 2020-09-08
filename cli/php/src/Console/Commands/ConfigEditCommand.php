<?php

namespace Laraboot\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

class ConfigEditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:edit {path} {key} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit a configuration value and persist the result in file';

    protected function getOptions()
    {
        return [
            ['env', InputOption::VALUE_OPTIONAL, 'the key to modify e.g `asset_url` .']
        ];
    }

    protected function getArguments()
    {
        return [
            ['key', InputArgument::REQUIRED, 'the key to modify e.g `asset_url` .'],
            ['path', InputArgument::REQUIRED, 'The path to edith e.g config.app = config/app.php'],
            ['value', InputArgument::REQUIRED, 'the new value'],
        ];
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $binPath = base_path() . '/vendor/oscarnevarezleal/laravel-sed/cli/php/main.php';
        $commandStr = sprintf('php %s -a config.edit -p %s -v %s -d %s',
            $binPath,
            $this->argument('key'),
            $this->argument('value'),
            base_path());

        if ($this->hasOption('env')) {
            $orValue = sprintf('%s|%s', $this->option('env'), $this->argument('value'));
            $commandStr .= sprintf(' -e %s', $orValue);
        }

        $command = explode(' ', $commandStr);
        $process = new Process($command);
        $process->run();

        echo $process->getOutput();
        return $process->getExitCode();
    }
}
