<?php
/*
 * Copyright (c) 2021. Oscar Nevarez Leal <fu.wire@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Laraboot;

use Laraboot\Commands\ApplyPresetCommand;
use Laraboot\Commands\EditConfigFileCommand;
use Laraboot\Commands\GenerateConfigFile;
use Symfony\Component\Console\Application;

final class LarasedApplication extends Application
{
    /**
     * LarasedApplication constructor.
     */
    public function __construct(string $name = 'Larased', string $version = '0.0.4')
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