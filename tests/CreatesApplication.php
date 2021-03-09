<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        require_once __DIR__ . '/../../../app/vendor/autoload.php';

        $app = require __DIR__ . '/../../../app/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
