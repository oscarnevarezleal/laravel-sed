<?php


namespace Laraboot\Presets;

use Laraboot\IPreset;

/**
 * This class ensures that none of the default values found in the config file assume
 * that we're using a monolithic/centric approach.
 * Class Cloudify
 * @package Laraboot\Presets
 */
final class CreateConfig implements IPreset
{
    /**
     * @return mixed[]
     */
    function getVisitors(): array
    {
        return [];
    }

    function setContext($context): void
    {
        // TODO: Implement setContext() method.
    }

    function execute(): void
    {
        // TODO: Implement execute() method.
    }
}