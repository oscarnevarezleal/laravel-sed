<?php

namespace Laraboot\Commands;

use Laraboot\Console\CliInputDefinition;
use Laraboot\EditCommand;
use Laraboot\FileVistorsTransformer;
use Laraboot\TopLevelInputConfig;
use Laraboot\Visitor\AppendArrayItemsVisitor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
        $output->writeln($transformed);

        // ... put here the code to run in your command

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
//        return Command::SUCCESS;
        return 0;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }

    /**
     * @return string[]
     */
    protected function getVisitors(): array
    {
        return [
            AppendArrayItemsVisitor::class
        ];
    }

}
