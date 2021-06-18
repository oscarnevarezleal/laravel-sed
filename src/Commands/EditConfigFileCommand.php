<?php

namespace Laraboot\Commands;

use Laraboot\Console\CliInputDefinition;
use Laraboot\EditCommand;
use Laraboot\FileVistorsTransformer;
use Laraboot\Schema\VisitorContext;
use Laraboot\TopLevelInputConfig;
use Laraboot\Visitor\AppendArrayItemsVisitor;
use Laraboot\Visitor\ChangeArrayValueVisitor;
use Laraboot\Visitor\ChangeNestedArrayValueVisitor;
use PhpParser\NodeVisitor\NodeConnectingVisitor;
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
    /**
     * @var string
     */
    protected static $defaultName = 'larased:config-edit';

    protected function configure(): void
    {
        $this->setDescription('Edits a config file');
        $this->setDefinition(new CliInputDefinition());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $pathValue = $input->getArgument(TopLevelInputConfig::INPUT_PATH_KEY);
        $pathDef = $this->getPathDefinition($pathValue);

        $basePathOption = $input->getOption('basePath');
        $basePath = $basePathOption ?? $this->getAppDirectory();

        $filename = sprintf('%s/%s.php', $basePath, $pathDef->getFileName());

        $visitorContext = $this->getVisitorContext($input, $pathDef);
        $visitors = $this->getVisitors();

        $transformer = new FileVistorsTransformer($filename, $visitors, $visitorContext);

        if ($visitorContext->getContext()[VisitorContext::MODE] === 'default') {
            $transformer->addVisitor(ChangeArrayValueVisitor::class);
        } else {
            $transformer->addVisitor(ChangeNestedArrayValueVisitor::class);
        }

        $transformer->addVisitor(AppendArrayItemsVisitor::class);

        if ($output->isVerbose()) {
            $output->writeln(sprintf('Running command from %s', getcwd()));
            $output->writeln(sprintf('Editing %s file', $pathDef->getFileName()));
            $output->writeln(sprintf('Property path %s will be substituted', $pathDef->getPropertyPath()));
        }

        $transformed = $transformer->transform();

        if ($output->isDebug()) {
            $output->writeln('Writing transformed file ' . $filename);
//            $output->writeln($transformed);
        }

        file_put_contents($filename, $transformed);

        return 0;
    }

    /**
     * @return array<class-string<NodeConnectingVisitor>>
     */
    protected function getVisitors(): array
    {
        return [
            NodeConnectingVisitor::class
        ];
    }

}
