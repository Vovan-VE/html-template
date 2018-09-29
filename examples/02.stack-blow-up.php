<?php

require __DIR__ . '/../vendor/autoload.php';

use VovanVE\HtmlTemplate\caching\memory\CacheStrings;
use VovanVE\HtmlTemplate\compile\Compiler;
use VovanVE\HtmlTemplate\Engine;
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;
use VovanVE\HtmlTemplate\source\memory\TemplateStringProvider;

class FooComponent extends \VovanVE\HtmlTemplate\components\BaseComponent
{
    /** @var string */
    public $level;

    /**
     * @param RuntimeHelperInterface $runtime
     * @param \Closure|null $content
     * @return string
     */
    public function render(RuntimeHelperInterface $runtime, ?\Closure $content = null): string
    {
        if (null === $content) {
            return "";
        }
        return join('', $content($runtime));
    }
}

$input = 'From the depth.';
for ($i = 500; $i > 0; $i--) {
    $input = "<Foo level='$i'>$input</Foo>";
}

$engine = (new Engine)
    ->setCache(new CacheStrings('CacheTempRuntime_%{hash}'))
    ->setTemplateProvider(
        (new TemplateStringProvider)
            ->setTemplate('foo', $input)
    )
    ->setCompiler(new Compiler());

$runtime = (new RuntimeHelper)
    ->setComponents([
        'Foo' => FooComponent::class,
    ]);

$compiled = $engine->compileTemplate('foo');

echo "Compiled code:", PHP_EOL;
echo "<<<<<<<<", PHP_EOL;
echo $compiled->getContent(), PHP_EOL;
echo ">>>>>>>>", PHP_EOL;
echo PHP_EOL;
echo "Template output:", PHP_EOL;
echo "<<<<<<<<", PHP_EOL;
echo $compiled->run($runtime);
echo PHP_EOL;
echo ">>>>>>>>", PHP_EOL;
