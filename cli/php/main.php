<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 8/20/2020
 * Time: 9:02 AM
 */
// There are two autoload files loaded here
// The first oen is from our laravel installation
// and the second one atken from this project
define('REL_PATH', '../../app/');
require_once REL_PATH . 'vendor/autoload.php';
require_once './vendor/autoload.php';

use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;


// let's assume we know the whole path of the file we need to modify
$filePath = REL_PATH.'/config/app.php';
$code = file_get_contents($filePath);
//$code = <<<'CODE'
//<?php
//
//function test($foo)
//{
//    var_dump($foo);
//}
//CODE;

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$dumper = new NodeDumper;
echo $dumper->dump($ast) . "\n";
