<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$project_path = getenv('LARAVEL_APP_DIR');
$finder = Finder::create()
    ->in([
        $project_path . '/app',
        $project_path . '/config',
        $project_path . '/database',
        $project_path . '/resources',
        $project_path . '/routes',
        $project_path . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new PhpCsFixer\Config();

$config->setRules(array_merge($config->getRules(), [
    '@PHP80Migration' => true,
    '@PHP80Migration:risky' => true,
    'heredoc_indentation' => false,
]));

return $config
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);