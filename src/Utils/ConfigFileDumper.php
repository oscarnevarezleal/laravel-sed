<?php


namespace Laraboot\Utils;

use PhpParser\Parser;
use Laraboot\Schema\VisitorContext;
use Laraboot\Visitor\AppendArrayItemsVisitor;
use PhpParser\{NodeTraverser};
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

final class ConfigFileDumper
{
    /**
     * @var Parser
     */
    private $parser;
    /**
     * @var VisitorContext $context
     */
    private $context;
    /**
     * @var string
     */
    private const BOILER_PLATE = '<?php return []; ';

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

    public function getContext(): VisitorContext
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

        $stmts = $this->parser->parse(self::BOILER_PLATE);

        $ast = $traverser->traverse($stmts);

        return $prettyPrinter->prettyPrintFile($ast);
    }

    /**
     * @return array<class-string<AppendArrayItemsVisitor>>
     */
    protected function getVisitors(): array
    {
        return [
            AppendArrayItemsVisitor::class
        ];
    }


}