<?php


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