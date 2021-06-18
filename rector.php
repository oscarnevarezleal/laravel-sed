<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php80\Rector\If_\NullsafeOperatorRector;
use Rector\Php80\Rector\NotIdentical\StrContainsRector;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::AUTOLOAD_PATHS, [
        __DIR__ . '/vendor/autoload.php',
    ]);

    $containerConfigurator->import(SetList::CODE_QUALITY);

    // paths to refactor; solid alternative to CLI arguments
    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/tests']);

    // is there a file you need to skip?
    $parameters->set(Option::SKIP, [
        __DIR__ . '/vendor/*',
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

    // Services
    $services = $containerConfigurator->services();
    $services->set(TypedPropertyRector::class);
    $services->set(ClosureToArrowFunctionRector::class);
    $services->set(ChangeSwitchToMatchRector::class);
    $services->set(AnnotationToAttributeRector::class);
    $services->set(ClassPropertyAssignToConstructorPromotionRector::class);
    $services->set(NullsafeOperatorRector::class);
    $services->set(StrContainsRector::class);
};
