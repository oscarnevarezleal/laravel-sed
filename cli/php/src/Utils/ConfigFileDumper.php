<?php


namespace Laraboot\Utils;

use Laraboot\Schema\VisitorContext;
use Laraboot\Visitor\AppendArrayItemsVisitor;
use PhpParser\{NodeTraverser, PrettyPrinter};
use PhpParser\ParserFactory;

class ConfigFileDumper
{
    private $parser;

    private string $filename;

    private VisitorContext $context;

    /**
     * ConfigFileDumper constructor.
     * @param VisitorContext $context
     */
    public function __construct(VisitorContext $context = null)
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        if ($context) {
            $this->context = $context;
        }
    }

    /**
     * @return VisitorContext|null
     */
    public function getContext(): ?VisitorContext
    {
        return $this->context;
    }

    /**
     * @param VisitorContext $context
     */
    public function setContext(VisitorContext $context): void
    {
        $this->context = $context;
    }


    /**
     * @return string
     */
    public function execute(): string
    {
        $traverser = new NodeTraverser();
        $prettyPrinter = new PrettyPrinter\Standard;

        foreach ($this->getVisitors() as $visitorClass) {
            $visitor = new $visitorClass($this->context);
            $traverser->addVisitor($visitor);
        }

        $boilerPlate = '<?php return []; ';

        $stmts = $this->parser->parse($boilerPlate);

        $ast = $traverser->traverse($stmts);

        $print = $prettyPrinter->prettyPrintFile($ast);

        return $print;
    }

    /**
     * @return mixed
     */
    protected function getVisitors()
    {
        return [
            AppendArrayItemsVisitor::class
        ];
    }


}