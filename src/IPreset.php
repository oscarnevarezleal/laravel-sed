<?php


namespace Laraboot;


interface IPreset
{
    function getVisitors():array;
    function setContext($context);
    function execute();
}