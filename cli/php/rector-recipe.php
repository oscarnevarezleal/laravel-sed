<?php

declare(strict_types=1);

use PhpParser\Node\Expr\MethodCall;
use Rector\RectorGenerator\Provider\RectorRecipeProvider;
use Rector\RectorGenerator\ValueObject\RectorRecipe;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Rector\SymfonyPhpConfig\inline_single_object;

// run "bin/rector generate" to a new Rector basic schema + tests from this config
return static function (ContainerConfigurator $containerConfigurator): void {
    // [REQUIRED]

    $rectorRecipe = new RectorRecipe(
    // name, basically short class name; use PascalCase
        'RenameMethodCallRector',

        // particular node types to change
        // the best practise is to have just 1 type here if possible, and make separated rule for other node types
        [MethodCall::class],

        // describe what the rule does
        '"something()" will be renamed to "somethingElse()"',

        // code before change
        // this is used for documentation and first test fixture
        <<<'CODE_SAMPLE'
<?php

class SomeClass
{
    public function run()
    {
        $this->something();
    }
}

CODE_SAMPLE

        // code after change
        , <<<'CODE_SAMPLE'
<?php

class SomeClass
{
    public function run()
    {
        $this->somethingElse();
    }
}
CODE_SAMPLE
    );

    // [OPTIONAL - UNCOMMENT TO USE]

    // links to useful websites, that explain why the change is needed
    // $rectorRecipe->setResources([
    //      'https://github.com/symfony/symfony/blob/704c648ba53be38ef2b0105c97c6497744fef8d8/UPGRADE-6.0.md'
    // ]);

    // do you need to check for extra generated file?
    // $rectorRecipe->addExtraFile('SomeExtraFile.php', '<?php ... SomeExtraFileContent');

    // is the rule configurable? add default configuration here
    // $rectorRecipe->setConfiguration(['SOME_CONSTANT_KEY' => ['before' => 'after']]);


    // [RECTOR CORE CONTRIBUTION]
    // [RECTOR CORE CONTRIBUTION - REQUIRED]
    // package name, basically part namespace in `rule/<package>/src`, use PascalCase
    // $rectorRecipe->setPackage('Generic');

    // [RECTOR CORE CONTRIBUTION - OPTIONAL]
    // set the rule belongs to; is optional, because e.g. generic rules don't need a specific set to belong to
    // $rectorRecipe->setSet(\Rector\Set\ValueObject\SetList::NAMING);

    $services = $containerConfigurator->services();

    $services->set(RectorRecipeProvider::class)
        ->arg('$rectorRecipe', inline_single_object($rectorRecipe, $services));
};