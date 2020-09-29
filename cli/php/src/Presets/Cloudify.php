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
class Cloudify extends PresetBase
{
    /**
     * @return string[]
     */
    function getVisitors(): array
    {
        return [ConfigFileDumper::class];
    }

    public function execute()
    {
        echo 'executing';

        foreach ($this->getVisitors() as $visitor) {
            /** @var IPreset $visitor */
            $v = new $visitor();
            if ($v instanceof IPreset && ([$v, 'setContext'])) {
                $v->setContext($this->getContext());
                $v->execute();
            }
        }
    }

}