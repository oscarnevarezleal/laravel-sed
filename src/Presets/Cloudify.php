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


namespace Laraboot\Presets;

use Laraboot\IPreset;
use Laraboot\PresetBase;
use Laraboot\Utils\ConfigFileDumper;

/**
 * This class ensures that none of the default values found in the config file assume
 * that we're using a monolithic/centric approach.
 * Class Cloudify
 * @package Laraboot\Presets
 */
final class Cloudify extends PresetBase
{
    /**
     * @return array<class-string<ConfigFileDumper>>
     */
    function getVisitors(): array
    {
        return [ConfigFileDumper::class];
    }

    public function execute(): void
    {
        echo 'executing';

        foreach ($this->getVisitors() as $visitor) {
            $v = new $visitor();
            $v->setContext($this->getContext());
            $v->execute();
        }
    }

}