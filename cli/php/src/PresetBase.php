<?php


namespace Laraboot;

use Laraboot\IPreset;
use Laraboot\Utils\ConfigFileDumper;

/**
 * Class PresetBase
 * @package Laraboot
 */
abstract class PresetBase implements IPreset
{
    private $context;

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string[]
     */
    abstract function getVisitors(): array;

    abstract public function execute();

    public function setContext($context)
    {
        $this->context = $context;
    }
}