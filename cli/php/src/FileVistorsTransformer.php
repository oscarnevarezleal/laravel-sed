<?php


namespace Laraboot;

use Laraboot\Schema\VisitorContext;
use PhpParser\{NodeTraverser, PrettyPrinter};
use PhpParser\ParserFactory;

class FileVistorsTransformer
{
    private $parser;

    private string $filename;

    private VisitorContext $visitorContext;

    /**
     * FileVistorsTransformer constructor.
     * @param $filename
     * @param array $visitors
     * @param array $options
     */
    public function __construct($filename, array $visitors, VisitorContext $visitorContext)
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        $this->filename = $filename;
        $this->visitors = $visitors;
        $this->visitorContext = $visitorContext;
    }


    /**
     * @return string
     */
    public function transform(): string
    {
        $traverser = new NodeTraverser();
        $prettyPrinter = new PrettyPrinter\Standard;

        foreach ($this->visitors as $visitorClass) {
            $visitor = new $visitorClass($this->visitorContext);
            $traverser->addVisitor($visitor);
        }

        $stmts = $this->parseFileContent();

        $ast = $traverser->traverse($stmts);

        $print = $prettyPrinter->prettyPrintFile($ast);

        return $print;
    }

    /**
     * @param string $filePath
     * @return false|string
     */
    public function readFilePath()
    {
        $code = file_get_contents($this->filename, true);
        return $code;
    }

    /**
     * @return \PhpParser\Node\Stmt[]|null
     */
    public function parseFileContent(): array
    {
        $code = $this->readFilePath();
        $stmts = $this->parser->parse($code);
        return $stmts;
    }

}