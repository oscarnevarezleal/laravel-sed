<?php


namespace Laraboot\Utils;

use Laraboot\Schema\VisitorContext;
use Laraboot\Visitor\AppendArrayItemsVisitor;
use PhpParser\{NodeTraverser};
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

class ConfigFileDumper
{
    private $parser;
    /**
     * @var VisitorContext $context
     */
    private $context;

    /**
     * ConfigFileDumper constructor.
     */
    public function __construct(VisitorContext $context = null)
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        if ($context !== null) {
            $this->context = $context;
        }
    }

    public function getContext(): ?VisitorContext
    {
        return $this->context;
    }

    public function setContext(VisitorContext $context): void
    {
        $this->context = $context;
    }


    public function execute(): string
    {
        $traverser = new NodeTraverser();
        $prettyPrinter = new Standard;

        foreach ($this->getVisitors() as $visitorClass) {
            $visitor = new $visitorClass($this->context);
            $traverser->addVisitor($visitor);
        }

        $boilerPlate = '<?php return []; ';

        $stmts = $this->parser->parse($boilerPlate);

        $ast = $traverser->traverse($stmts);

        return $prettyPrinter->prettyPrintFile($ast);
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