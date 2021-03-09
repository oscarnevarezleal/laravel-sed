<?php


namespace Laraboot\Presets;

use Laraboot\IPreset;

/**
 * This class ensures that none of the default values found in the config file assume
 * that we're using a monolithic/centric approach.
 * Class Cloudify
 * @package Laraboot\Presets
 */
class CreateConfig implements IPreset
{
    function getVisitors(): array
    {
        return [];
    }

    function setContext($context)
    {
        // TODO: Implement setContext() method.
    }

    function execute()
    {
        // TODO: Implement execute() method.
    }
}