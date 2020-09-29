<?php
// rector.php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

//use Utils\Rector\MyFirstRector;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
//    $services = $containerConfigurator->services();

    // paths to refactor; solid alternative to CLI arguments
    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/tests']);

    // is there a file you need to skip?
    $parameters->set(Option::EXCLUDE_PATHS, [
        __DIR__ . '/src/Services/*',
        __DIR__ . '/src/Console/Commands/*'
    ]);

    // here we can define, what sets of rules will be applied
    $parameters->set(Option::SETS, [SetList::CODE_QUALITY]);

    // auto import fully qualified class names? [default: false]
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    // skip root namespace classes, like \DateTime or \Exception [default: true]
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);
    // skip classes used in PHP DocBlocks, like in /** @var \Some\Class */ [default: true]
    $parameters->set(Option::IMPORT_DOC_BLOCKS, true);

    // is your PHP version different from the one your refactor to? [default: your PHP version]
    $parameters->set(Option::PHP_VERSION_FEATURES, '7.3');
};