<?php
// rector.php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    // paths to refactor; solid alternative to CLI arguments
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // is there a file you need to skip?
    $parameters->set(Option::EXCLUDE_PATHS, [
        // single file
        __DIR__ . '/cli/php/src/MyServiceProvider.php',
        __DIR__ . '/cli/php/src/Console/Commands/ConfigEditCommand.php',
        // or fnmatch
        __DIR__ . '/cli/php/src/*/Tests/*',
    ]);

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $parameters->set(Option::AUTOLOAD_PATHS, [
        // autoload specific file
//        __DIR__ . '/vendor/squizlabs/php_codesniffer/autoload.php',
        // or full directory
//        __DIR__ . '/vendor/project-without-composer',
    ]);

    // is there single rule you don't like from a set you use?
    $parameters->set(Option::EXCLUDE_RECTORS, [
//        SimplifyIfReturnBoolRector::class,
    ]);

    // is your PHP version different from the one your refactor to? [default: your PHP version]
    $parameters->set(Option::PHP_VERSION_FEATURES, '7.2');

    // auto import fully qualified class names? [default: false]
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    // skip root namespace classes, like \DateTime or \Exception [default: true]
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);
    // skip classes used in PHP DocBlocks, like in /** @var \Some\Class */ [default: true]
    $parameters->set(Option::IMPORT_DOC_BLOCKS, false);
};