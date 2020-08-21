<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 8/20/2020
 * Time: 8:28 PM
 */

namespace Laraboot;

use Illuminate\Support\ServiceProvider;
use Laraboot\Console\Commands\ConfigEditCommand;

class MyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ConfigEditCommand::class,
            ]);
        }
    }
}