<?php

namespace Laraboot;

use Symfony\Component\Console\Application;

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
    }


}