<?php

require __DIR__ . '/../vendor/autoload.php';

use VovanVE\HtmlTemplate\caching\memory\CacheStrings;
use VovanVE\HtmlTemplate\compile\Compiler;
use VovanVE\HtmlTemplate\components\BaseComponent;
use VovanVE\HtmlTemplate\Engine;
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;
use VovanVE\HtmlTemplate\source\memory\TemplateStringProvider;

class LoopComponent extends BaseComponent
{
    public $var = 'i';
    public $from = 0;
    public $to = 0;
    public $step;

    public function render(RuntimeHelperInterface $runtime, ?\Closure $content = null): string {
        if (!$content) {
            return '';
        }

        $result = '';

        $from = (int)$this->from;
        $to = (int)$this->to;
        $step = $this->step ?: ($to >= $from ? 1 : -1);
        for ($i = $from; $step > 0 ? $i <= $to : $i >= $to; $i += $step) {
            $result .= join('', $content($runtime->addParams([
                $this->var => $i,
            ])));
        }

        return $result;
    }
}

$engine = (new Engine)
    ->setCache(new CacheStrings('CacheTempRuntime_%{hash}'))
    ->setTemplateProvider(
        (new TemplateStringProvider)
            ->setTemplate(
                'foo',
                <<<'TPL'
<div>
    Loop for `i`:
    <Loop to="20" step="3">
        <i i={i}/>
    </Loop>
</div>
<div>
    <span>
        foo=<samp>{foo}</samp>
    </span>
    <Loop var="foo" from="3" to="1">
        <i foo={foo}/>
    </Loop>
    <span>
        foo=<samp>{foo}</samp>
    </span>
</div>
TPL
            )
    )
    ->setCompiler(new Compiler());

$runtime = (new RuntimeHelper)
    ->addParams([
        'foo' => 42,
    ])
    ->addComponents([
        'Loop' => LoopComponent::class,
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
