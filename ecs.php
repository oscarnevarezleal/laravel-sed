<?php

use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoWhitespaceBeforeCommaInArrayFixer;
use PhpCsFixer\Fixer\ArrayNotation\WhitespaceAfterCommaInArrayFixer;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Arrays\DisallowLongArraySyntaxSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return ECSConfig::configure()
    ->withPaths(['/var/app'])
    ->withRules([
        ArraySyntaxFixer::class,
        NoWhitespaceBeforeCommaInArrayFixer::class,
        WhitespaceAfterCommaInArrayFixer::class,
        DisallowLongArraySyntaxSniff::class,
    ])
    ->withSkip([ConcatSpaceFixer::class])
    ->withSets([SetList::PSR_12])
    ->withPhpCsFixerSets(
    doctrineAnnotation: true, 
    psr1: true,
    psr2: true,
    psr12: true, 
    perCS20: true, 
    symfony: true, 
    phpCsFixer: true);