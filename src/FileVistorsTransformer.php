<?php


namespace Laraboot;

use Laraboot\Schema\VisitorContext;
use PhpParser\{NodeTraverser};
use PhpParser\Node\Stmt;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

class FileVistorsTransformer
{
    /**
     * @var mixed[]|mixed
     */
    public $visitors;
    private $parser;

    private $filename;

    /**
     * @var VisitorContext
     */
    private $visitorContext;

    /**
     * FileVistorsTransformer constructor.
     * @param string $filename
     * @param array $visitors
     * @param array $options
     */
    public function __construct(string $filename, array $visitors, VisitorContext $visitorContext)
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
     * @return false|string
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
     * @return FileVistorsTransformer
     */
    public function addVisitor($visitor)
    {
        $this->visitors[] = $visitor;
        return $this;
    }

}