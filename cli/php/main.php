<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 8/20/2020
 * Time: 9:02 AM
 */
// There are two autoload files loaded here
// The first oen is from our laravel installation
// and the second one taken from this project
error_reporting(E_ALL);

$shortopts = "";
$shortopts .= "a:"; // Required value
$shortopts .= "p:"; // Required value
$shortopts .= "e:"; // Required value
$shortopts .= "v:"; // These options do not accept values
$shortopts .= "d:";

$longopts = array(
    "action:",  // Required value
    "path:",    // Required value
    "value:",   // Required value
    "envor::",  // Required value
    "directory",    // No value
);

$options = getopt($shortopts, $longopts);

if (getenv('CLI_BIN_DIR')) {
    define('APP_BASE_PATH', getenv('CLI_BIN_DIR') . '/app');
} else if (isset($options['d'])) {
    define('APP_BASE_PATH', $options['d']);
} else {
    define('APP_BASE_PATH', './');
}

echo 'APP_BASE_PATH=' . APP_BASE_PATH . "\n";

require_once APP_BASE_PATH . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

use PhpParser\{NodeDumper, NodeTraverser, PrettyPrinter};
use PhpParser\ParserFactory;

print_r($options);

$modifiers = [
    'config.edit' => Laraboot\Visitor\ChangeArrayValueVisitor::class,
    'config.append_array_value' => Laraboot\Visitor\AppendArrayValueVisitor::class
];

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

try {

    $visitor = array_key_exists($options['a'], $modifiers) ? new $modifiers[$options['a']]($options) : null;

    if (!$visitor) {
        echo sprintf('Unknown modifier specified %s', $options['a']);
        exit;
    }

    // let's assume we know the whole path of the file we need to modify
    $filePath = APP_BASE_PATH . '/config/app.php';

    $code = file_get_contents($filePath);

    $dumper = new NodeDumper;

    $traverser = new NodeTraverser();

    $traverser->addVisitor($visitor);

    $stmts = $parser->parse($code);

    $ast = $traverser->traverse($stmts);

    $prettyPrinter = new PrettyPrinter\Standard;
    
    $print = $prettyPrinter->prettyPrintFile($ast);

    file_put_contents($filePath, $print);
    
    echo $print;

} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}