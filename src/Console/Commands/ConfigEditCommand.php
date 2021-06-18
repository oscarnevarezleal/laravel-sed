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

namespace Laraboot\Console\Commands;

use Laraboot\Commands\EditConfigFileCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

final class ConfigEditCommand extends EditConfigFileCommand
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

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
     * @return InputOption[]
     */
    protected function getOptions()
    {
        return $this->getDefinition()->getOptions();
    }

    /**
     * @return InputArgument[]
     */
    protected function getArguments()
    {
        return $this->getDefinition()->getArguments();
    }
}
