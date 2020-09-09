<?php

namespace Laraboot\Commands;

use Laraboot\Console\CliInputDefinition;
use Laraboot\EditCommand;
use Laraboot\FileVistorsTransformer;
use Laraboot\TopLevelInputConfig;
use Laraboot\Visitor\AppendArrayItemsVisitor;
use Laraboot\Visitor\ChangeArrayValueVisitor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function file_put_contents;
use function getcwd;
use function sprintf;

/**
 * Class EditConfigFileCommand
 * @package Laraboot\Commands
 */
class EditConfigFileCommand extends EditCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'larased:config-edit';

    protected function configure()
    {
        $this->setDescription('Edits a config file');
        $this->setDefinition(new CliInputDefinition());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $pathValue = $input->getArgument(TopLevelInputConfig::INPUT_PATH_KEY);
        $pathDef = $this->getPathDefinition($pathValue);

        $basePathOption = $input->getOption('basePath');
        $basePath = $basePathOption ?? $this->getAppDirectory();

        $filename = sprintf('%s/%s.php', $basePath, $pathDef->getFileName());

        $visitorContext = $this->getVisitorContext($input, $pathDef);

        $transformer = new FileVistorsTransformer($filename, $this->getVisitors(), $visitorContext);

        $output->writeln(sprintf('Running command from %s', getcwd()));
        $output->writeln(sprintf('Editing %s file', $pathDef->getFileName()));
        $output->writeln(sprintf('Property path %s will be substituted', $pathDef->getPropertyPath()));

        $transformed = $transformer->transform();

        if ($output->isVerbose()) {
            $output->writeln($transformed);
        }

        file_put_contents($filename, $transformed);

        return 0;
    }

    /**
     * @return string[]
     */
    protected function getVisitors(): array
    {
        return [
            ChangeArrayValueVisitor::class,
            AppendArrayItemsVisitor::class
        ];
    }

}
