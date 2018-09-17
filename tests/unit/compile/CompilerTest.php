<?php
namespace VovanVE\HtmlTemplate\tests\unit\compile;

use VovanVE\HtmlTemplate\compile\CompileException;
use VovanVE\HtmlTemplate\compile\Compiler;
use VovanVE\HtmlTemplate\source\memory\TemplateString;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;
use VovanVE\HtmlTemplate\tests\helpers\conversion\Expect;
use VovanVE\HtmlTemplate\tests\helpers\StringConversionTestTrait;

class CompilerTest extends BaseTestCase
{
    use StringConversionTestTrait;

    private const PATH = __DIR__ . '/cases';
    private const EXTENSION = '.t';

    /**
     * @return Compiler
     */
    public function testCreate(): Compiler
    {
        $this->expectNotToPerformAssertions();
        return new Compiler();
    }

    /**
     * @param Expect $expect
     * @param string $filename
     * @param Compiler $compiler
     * @throws \VovanVE\HtmlTemplate\compile\CompileException
     * @dataProvider dataProvider
     * @depends testCreate
     */
    public function testCompile($expect, $filename, $compiler)
    {
        $template = new TemplateString($expect->getSource(), $filename);

        $expect->setExpectations($this);

        /** @noinspection PhpUnhandledExceptionInspection */
        $result = $compiler->compile($template);

        $expect->checkResult($this, $result->getContent());
    }

    /**
     * @param Compiler $compiler
     * @depends testCreate
     */
    public function testCompileSpaces($compiler)
    {
        // this test is separated to prevent stripping trailing whitespaces
        // on file save

        $template = new TemplateString(join("\n", [
            '',
            '  ',
            'lorem,',
            'ipsum,  ',
            '  dolor,',
            '  sit,  ',
            '',
            '  ',
            '  ',
            '',
            '  amet,  ',
            '',
            'consectepture.',
            '',
            '  ',
            '',
        ]), '');

        /** @noinspection PhpUnhandledExceptionInspection */
        $result = $compiler->compile($template);

        $this->assertEquals('lorem,ipsum,dolor,sit,amet,consectepture.', $result->getContent());
    }

    public function testDisabledElements()
    {
        foreach (
            [
                ['script'],
                ['SCRIPT'],
                ['SCRipt'],
                ['script', 'STYLE', 'MEta'],
            ]
            as $disabled
        ) {
            $compiler = (new Compiler)->setDisabledElements($disabled);

            foreach (
                [
                    'text <a/> <script>',
                    'text <a/> <SCRipt>',
                    'text <a/> <SCRIPT>',
                    'text <a/> <script >',
                    'text <a/> <script foo="bar">',
                    'text <a/> <script/>',
                    'text <a/> <script />',
                    'text <a/> </script>',
                    'text <a/> </script >',
                ]
                as $source
            ) {
                $template = new TemplateString($source, '');
                try {
                    $compiler->compile($template);
                    $this->fail(
                        "Disabled HTML element (" . join(", ", $disabled)
                        . ") did compiled from `$source`"
                    );
                } catch (CompileException $e) {
                    $this->assertRegExp(
                        '/^HTML Element `<(?i:script)>` is not allowed$/D',
                        $e->getMessage(),
                        'thrown exception message'
                    );
                }
            }
        }
    }

    /**
     * @param string $disabled
     * @param string $source
     * @param string $blocked
     * @dataProvider dataProviderDisabledElements
     */
    public function testDisabledElementsAll($disabled, $source, $blocked)
    {
        $compiler = (new Compiler)->setDisabledElements([$disabled]);
        $template = new TemplateString($source, '');

        try {
            $compiler->compile($template);
            $this->fail("Disabled HTML element ($disabled) did compiled from `$source`");
        } catch (CompileException $e) {
            $this->assertEquals(
                "HTML Element `<$blocked>` is not allowed",
                $e->getMessage(),
                "thrown exception message from"
            );
        }
    }

    public function dataProvider()
    {
        yield from $this->expectationDataProvider(self::PATH, self::EXTENSION);
    }

    public function dataProviderDisabledElements()
    {
        return [
            ['*', 'text <div> text <span> text', 'div'],
            ['*', 'text <Div> text <span> text', 'Div'],
            ['*', 'text <DIV> text <span> text', 'DIV'],
            ['*', 'text </div> text </span> text', 'div'],
            ['*', 'text <a:b> text <c:d> text', 'a:b'],
            ['*', 'text <A:b> text <c:d> text', 'A:b'],
            ['*', 'text <A:B> text <c:d> text', 'A:B'],

            [':*', 'text <div> text <span> text', 'div'],
            [':*', 'text <Div> text <span> text', 'Div'],
            [':*', 'text <DIV> text <span> text', 'DIV'],
            [':*', 'text </div> text </span> text', 'div'],
            [':*', 'text <a:b> text <div> text', 'div'],
            [':*', 'text <A:b> text <div> text', 'div'],
            [':*', 'text <A:B> text <div> text', 'div'],

            ['*:*', 'text <div> text <a:b> text', 'a:b'],
            ['*:*', 'text <Div> text <a:B> text', 'a:B'],
            ['*:*', 'text <DIV> text </a:b> text', 'a:b'],

            ['span', 'text <div> text <span> text', 'span'],
            ['span', 'text <Div> text <Span> text', 'Span'],
            ['span', 'text <DIV> text <SPAN> text', 'SPAN'],
            ['span', 'text <a:b> text <span> text', 'span'],
            ['span', 'text <a:span> text <SPAN> text', 'SPAN'],

            [':span', 'text <div> text <span> text', 'span'],
            [':span', 'text <Div> text <Span> text', 'Span'],
            [':span', 'text <DIV> text <SPAN> text', 'SPAN'],
            [':span', 'text <a:b> text <span> text', 'span'],
            [':span', 'text <a:span> text <SPAN> text', 'SPAN'],

            ['c:*', 'text <a:b> text <c:d> text', 'c:d'],
            ['*:span', 'text <span> text <a:span> text', 'a:span'],
            ['a:span', 'text <span> text <a:span> text', 'a:span'],
        ];
    }
}
