<?php

namespace Laraboot;

use Laraboot\Schema\VisitorContext;
use Symfony\Component\Console\Command\Command;
use Laraboot\Schema\PathDefinition;
use Symfony\Component\Console\Input\InputInterface;
use function array_merge;
use function dirname;

class EditCommand extends Command
{

    /**
     * Splits the path into tokens and initialize a PathDefinition with those tokens
     * @param string|null $path
     * @return PathDefinition
     */
    protected function getPathDefinition(string $path): PathDefinition
    {
        return PathDefinition::fromString($path);
    }

    /**
     * @param InputInterface $input
     * @param PathDefinition|null $pathDef
     * @return VisitorContext
     */
    protected function getVisitorContext(InputInterface $input, \Laraboot\Schema\PathDefinition $pathDef = null): VisitorContext
    {
        $inputContext = array_merge($input->getArguments(), $input->getOptions());

        if ($pathDef) {
            $inputContext = array_merge($inputContext, $pathDef->asArray());
        }

        return new VisitorContext($pathDef, $inputContext);
    }
}