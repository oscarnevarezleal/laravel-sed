<?php


namespace Laraboot;

use PhpParser\Parser;
use Laraboot\Schema\VisitorContext;
use PhpParser\{NodeTraverser};
use PhpParser\Node\Stmt;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

final class FileVistorsTransformer
{
    /**
     * @var mixed[]|mixed
     */
    private $visitors;
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var VisitorContext
     */
    private $visitorContext;

    /**
     * FileVistorsTransformer constructor.
     * @param array $options
     */
    public function __construct(string $filename, array $visitors, VisitorContext $visitorContext)
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        $this->filename = $filename;
        $this->visitors = $visitors;
        $this->visitorContext = $visitorContext;
    }


    public function transform(): string
    {
        $traverser = new NodeTraverser();
        $prettyPrinter = new Standard;

        foreach ($this->visitors as $visitorClass) {
            $visitor = new $visitorClass($this->visitorContext);
            $traverser->addVisitor($visitor);
        }

        $stmts = $this->parseFileContent();

        $ast = $traverser->traverse($stmts);

        return $prettyPrinter->prettyPrintFile($ast);
    }

    /**
     * @param string $filePath
     * @return string|bool
     */
    public function readFilePath()
    {
        return file_get_contents($this->filename, true);
    }

    /**
     * @return Stmt[]|null
     */
    public function parseFileContent(): array
    {
        $code = $this->readFilePath();
        return $this->parser->parse($code);
    }


    /**
     * @param $visitor
     */
    public function addVisitor($visitor): self
    {
        $this->visitors[] = $visitor;
        return $this;
    }

}