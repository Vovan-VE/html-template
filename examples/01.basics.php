<?php

require __DIR__ . '/../vendor/autoload.php';

use VovanVE\HtmlTemplate\caching\memory\CacheStrings;
use VovanVE\HtmlTemplate\compile\Compiler;
use VovanVE\HtmlTemplate\Engine;
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;
use VovanVE\HtmlTemplate\source\memory\TemplateStringProvider;

$engine = (new Engine)
    ->setCache(new CacheStrings('CacheTempRuntime_%{hash}'))
    ->setTemplateProvider(
        (new TemplateStringProvider)
            ->setTemplate(
                'foo',
                <<<'TPL'
<a href={ link } title={ title && "Foo bar: ${ title }" }>
    <span id=foobar class='it parses html'>
        { description }
    </span>
    <span>
        {# comment #}
    </span>
</a>
TPL
            )
    )
    ->setCompiler(new Compiler());

$runtime = new RuntimeHelper([
    'link' => 'http://example.com?foo=bar&lorem=ipsum#hash',
    'title' => 'Lorem <ipsum> "dolor" sit amet',
    'description' => function () {
        return 'Some <text/plain> content';
    },
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
