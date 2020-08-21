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
$shortopts .= "a:";  // Required value
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

print_r($options);
echo 'APP_BASE_PATH=' . APP_BASE_PATH . "\n";

require_once APP_BASE_PATH . '/vendor/autoload.php';

@require_once dirname(__FILE__) . '/vendor/autoload.php';

use PhpParser\{NodeDumper, NodeTraverser, PrettyPrinter};
use PhpParser\ParserFactory;

// let's assume we know the whole path of the file we need to modify
$filePath = APP_BASE_PATH . '/config/app.php';

$code = file_get_contents($filePath);
//echo $code;

$modifiers = [
    'config.edit' => Laraboot\Visitor\ChangeArrayValueVisitor::class
];

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

try {
    $traverser = new NodeTraverser();
    $visitor = array_key_exists($options['a'], $modifiers) ? new $modifiers[$options['a']]($options) : null;
    if (!$visitor) {
        echo sprintf('Unknown modifier specified %s', $options['a']);
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