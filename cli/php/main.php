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
if (!empty(getenv('CLI_BIN_DIR'))) {
    define('REL_PATH', getenv('CLI_BIN_DIR') . '/');
} else {
    define('REL_PATH', './');
}

require_once REL_PATH . 'app/vendor/autoload.php';
require_once REL_PATH . 'cli/php/vendor/autoload.php';

use PhpParser\{NodeDumper, NodeTraverser, PrettyPrinter};
use PhpParser\ParserFactory;

$shortopts = "";
$shortopts .= "a:";  // Required value
$shortopts .= "p:"; // Required value
$shortopts .= "e:"; // Required value
$shortopts .= "v:"; // These options do not accept values
$shortopts .= "d";

$longopts = array(
    "action:",  // Required value
    "path:",    // Required value
    "value:",   // Required value
    "envor::",  // Required value
    "debug",    // No value
);
$options = getopt($shortopts, $longopts);

// let's assume we know the whole path of the file we need to modify
$filePath = 'app/config/app.php';
$code = file_get_contents($filePath);

$modifiers = [
    'config.edit' => LaraBoot\Visitor\ChangeArrayValueVisitor::class
];

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

try {
    $traverser = new NodeTraverser();
    $visitor = array_key_exists($options['a'], $modifiers) ? new $modifiers[$options['a']]($options) : null;
    if (!$visitor) {
        echo 'No modifier specified';
        exit;
    }
    $traverser->addVisitor($visitor);

    $dumper = new NodeDumper;
    $stmts = $parser->parse($code);

    $ast = $traverser->traverse($stmts);
//    echo $dumper->dump($ast) . "\n";
//    echo json_encode($stmts, JSON_PRETTY_PRINT), "\n";
    $prettyPrinter = new PrettyPrinter\Standard;

    file_put_contents($filePath, $prettyPrinter->prettyPrintFile($ast));

} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$dumper = new NodeDumper;
//echo $dumper->dump($ast) . "\n";
