<?php


namespace Laraboot;


interface IPreset
{
    /**
     * @return mixed[]
     */
    function getVisitors():array;
    function setContext($context);
    function execute();
}