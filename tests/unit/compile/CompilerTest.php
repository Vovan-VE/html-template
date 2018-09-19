<?php
namespace VovanVE\HtmlTemplate\tests\unit\compile;

use VovanVE\HtmlTemplate\compile\Compiler;
use VovanVE\HtmlTemplate\compile\SyntaxException;
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
     * @dataProvider dataProvider
     * @depends testCreate
     */
    public function testCompile(Expect $expect, string $filename, Compiler $compiler)
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
    public function testCompileSpaces(Compiler $compiler)
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
                } catch (SyntaxException $e) {
                    $this->assertRegExp(
                        '/^HTML Element `<(?i:script)>` is not allowed near `/D',
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
    public function testDisabledElementsAll(string $disabled, string $source, string $blocked)
    {
        $compiler = (new Compiler)->setDisabledElements([$disabled]);
        $template = new TemplateString($source, '');

        try {
            $compiler->compile($template);
            $this->fail("Disabled HTML element ($disabled) did compiled from `$source`");
        } catch (SyntaxException $e) {
            $this->assertStringMatchesFormat(
                "HTML Element `<$blocked>` is not allowed near `%s` in `` at line %d",
                $e->getMessage(),
                "thrown exception message"
            );
        }
    }

    /**
     * @param string $disabledElement
     * @param string $disabledAttribute
     * @param string $source
     * @param string $blockedElement
     * @param string $blockedAttribute
     * @dataProvider dataProviderDisabledAttributes
     */
    public function testDisabledAttributes(
        string $disabledElement,
        string $disabledAttribute,
        string $source,
        string $blockedElement,
        string $blockedAttribute
    ) {
        $compiler = (new Compiler)
            ->setDisabledAttributes([
                $disabledElement => [$disabledAttribute],
            ]);
        $template = new TemplateString($source, '');

        try {
            $compiler->compile($template);
            $this->fail(
                "Disabled HTML attribute ($disabledAttribute in element $disabledElement) did compiled from `$source`"
            );
        } catch (SyntaxException $e) {
            $this->assertStringMatchesFormat(
                "HTML attribute `$blockedAttribute` is not allowed in element `<$blockedElement>`"
                . " near `%s` in `` at line %d",
                $e->getMessage(),
                "thrown exception message"
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

    public function dataProviderDisabledAttributes()
    {
        return [
            ['*', '*', 'text <div id="foo">', 'div', 'id'],
            ['*', '*', "text <div id='foo'>", 'div', 'id'],
            ['*', '*', 'text <div id=foo>', 'div', 'id'],

            ['*', '*', 'text <div id>', 'div', 'id'],
            ['*', '*', 'text <DIV id>', 'DIV', 'id'],
            ['*', '*', 'text <a:b id>', 'a:b', 'id'],
            ['*', '*', 'text <A:b id>', 'A:b', 'id'],
            ['*', '*', 'text <A:B id>', 'A:B', 'id'],
            ['*', '*', 'text <div ID>', 'div', 'ID'],
            ['*', '*', 'text <DIV ID>', 'DIV', 'ID'],
            ['*', '*', 'text <a:b ID>', 'a:b', 'ID'],
            ['*', '*', 'text <A:b ID>', 'A:b', 'ID'],
            ['*', '*', 'text <A:B ID>', 'A:B', 'ID'],
            ['*', '*', 'text <div x:y>', 'div', 'x:y'],
            ['*', '*', 'text <DIV x:y>', 'DIV', 'x:y'],
            ['*', '*', 'text <a:b x:y>', 'a:b', 'x:y'],
            ['*', '*', 'text <A:b x:y>', 'A:b', 'x:y'],
            ['*', '*', 'text <A:B x:y>', 'A:B', 'x:y'],
            ['*', '*', 'text <div X:Y>', 'div', 'X:Y'],
            ['*', '*', 'text <DIV X:Y>', 'DIV', 'X:Y'],
            ['*', '*', 'text <a:b X:Y>', 'a:b', 'X:Y'],
            ['*', '*', 'text <A:b X:Y>', 'A:b', 'X:Y'],
            ['*', '*', 'text <A:B X:Y>', 'A:B', 'X:Y'],

            ['*', ':*', 'text <div id>', 'div', 'id'],
            ['*', ':*', 'text <DIV id>', 'DIV', 'id'],
            ['*', ':*', 'text <a:b id>', 'a:b', 'id'],
            ['*', ':*', 'text <A:b id>', 'A:b', 'id'],
            ['*', ':*', 'text <A:B id>', 'A:B', 'id'],
            ['*', ':*', 'text <div ID>', 'div', 'ID'],
            ['*', ':*', 'text <DIV ID>', 'DIV', 'ID'],
            ['*', ':*', 'text <a:b ID>', 'a:b', 'ID'],
            ['*', ':*', 'text <A:b ID>', 'A:b', 'ID'],
            ['*', ':*', 'text <A:B ID>', 'A:B', 'ID'],

            ['*', '*:*', 'text <div x:y>', 'div', 'x:y'],
            ['*', '*:*', 'text <DIV x:y>', 'DIV', 'x:y'],
            ['*', '*:*', 'text <a:b x:y>', 'a:b', 'x:y'],
            ['*', '*:*', 'text <A:b x:y>', 'A:b', 'x:y'],
            ['*', '*:*', 'text <A:B x:y>', 'A:B', 'x:y'],
            ['*', '*:*', 'text <div X:Y>', 'div', 'X:Y'],
            ['*', '*:*', 'text <DIV X:Y>', 'DIV', 'X:Y'],
            ['*', '*:*', 'text <a:b X:Y>', 'a:b', 'X:Y'],
            ['*', '*:*', 'text <A:b X:Y>', 'A:b', 'X:Y'],
            ['*', '*:*', 'text <A:B X:Y>', 'A:B', 'X:Y'],

            ['*', 'e', 'text <i id> <div id e>', 'div', 'e'],
            ['*', 'e', 'text <i id> <DIV id e>', 'DIV', 'e'],
            ['*', 'e', 'text <i id> <a:b id e>', 'a:b', 'e'],
            ['*', 'e', 'text <i id> <A:b id e>', 'A:b', 'e'],
            ['*', 'e', 'text <i id> <A:B id e>', 'A:B', 'e'],
            ['*', 'e', 'text <i id> <div ID E>', 'div', 'E'],
            ['*', 'e', 'text <i id> <DIV ID E>', 'DIV', 'E'],
            ['*', 'e', 'text <i id> <a:b ID E>', 'a:b', 'E'],
            ['*', 'e', 'text <i id> <A:b ID E>', 'A:b', 'E'],
            ['*', 'e', 'text <i id> <A:B ID E>', 'A:B', 'E'],
            ['*', 'e', 'text <i id> <div x:y e>', 'div', 'e'],
            ['*', 'e', 'text <i id> <DIV x:y e>', 'DIV', 'e'],
            ['*', 'e', 'text <i id> <a:b x:y e>', 'a:b', 'e'],
            ['*', 'e', 'text <i id> <A:b x:y e>', 'A:b', 'e'],
            ['*', 'e', 'text <i id> <A:B x:y e>', 'A:B', 'e'],
            ['*', 'e', 'text <i id> <div X:Y E>', 'div', 'E'],
            ['*', 'e', 'text <i id> <DIV X:Y E>', 'DIV', 'E'],
            ['*', 'e', 'text <i id> <a:b X:Y E>', 'a:b', 'E'],
            ['*', 'e', 'text <i id> <A:b X:Y E>', 'A:b', 'E'],
            ['*', 'e', 'text <i id> <A:B X:Y E>', 'A:B', 'E'],
            ['*', 'e', 'text <i id> <div x:e e>', 'div', 'e'],
            ['*', 'e', 'text <i id> <DIV x:e e>', 'DIV', 'e'],
            ['*', 'e', 'text <i id> <a:b x:e e>', 'a:b', 'e'],
            ['*', 'e', 'text <i id> <A:b x:e e>', 'A:b', 'e'],
            ['*', 'e', 'text <i id> <A:B x:e e>', 'A:B', 'e'],
            ['*', 'e', 'text <i id> <div X:E E>', 'div', 'E'],
            ['*', 'e', 'text <i id> <DIV X:E E>', 'DIV', 'E'],
            ['*', 'e', 'text <i id> <a:b X:E E>', 'a:b', 'E'],
            ['*', 'e', 'text <i id> <A:b X:E E>', 'A:b', 'E'],
            ['*', 'e', 'text <i id> <A:B X:E E>', 'A:B', 'E'],

            ['*', ':e', 'text <i id> <div id e>', 'div', 'e'],
            ['*', ':e', 'text <i id> <DIV id e>', 'DIV', 'e'],
            ['*', ':e', 'text <i id> <a:b id e>', 'a:b', 'e'],
            ['*', ':e', 'text <i id> <A:b id e>', 'A:b', 'e'],
            ['*', ':e', 'text <i id> <A:B id e>', 'A:B', 'e'],
            ['*', ':e', 'text <i id> <div ID E>', 'div', 'E'],
            ['*', ':e', 'text <i id> <DIV ID E>', 'DIV', 'E'],
            ['*', ':e', 'text <i id> <a:b ID E>', 'a:b', 'E'],
            ['*', ':e', 'text <i id> <A:b ID E>', 'A:b', 'E'],
            ['*', ':e', 'text <i id> <A:B ID E>', 'A:B', 'E'],
            ['*', ':e', 'text <i id> <div x:y e>', 'div', 'e'],
            ['*', ':e', 'text <i id> <DIV x:y e>', 'DIV', 'e'],
            ['*', ':e', 'text <i id> <a:b x:y e>', 'a:b', 'e'],
            ['*', ':e', 'text <i id> <A:b x:y e>', 'A:b', 'e'],
            ['*', ':e', 'text <i id> <A:B x:y e>', 'A:B', 'e'],
            ['*', ':e', 'text <i id> <div X:Y E>', 'div', 'E'],
            ['*', ':e', 'text <i id> <DIV X:Y E>', 'DIV', 'E'],
            ['*', ':e', 'text <i id> <a:b X:Y E>', 'a:b', 'E'],
            ['*', ':e', 'text <i id> <A:b X:Y E>', 'A:b', 'E'],
            ['*', ':e', 'text <i id> <A:B X:Y E>', 'A:B', 'E'],
            ['*', ':e', 'text <i id> <div x:e e>', 'div', 'e'],
            ['*', ':e', 'text <i id> <DIV x:e e>', 'DIV', 'e'],
            ['*', ':e', 'text <i id> <a:b x:e e>', 'a:b', 'e'],
            ['*', ':e', 'text <i id> <A:b x:e e>', 'A:b', 'e'],
            ['*', ':e', 'text <i id> <A:B x:e e>', 'A:B', 'e'],
            ['*', ':e', 'text <i id> <div X:E E>', 'div', 'E'],
            ['*', ':e', 'text <i id> <DIV X:E E>', 'DIV', 'E'],
            ['*', ':e', 'text <i id> <a:b X:E E>', 'a:b', 'E'],
            ['*', ':e', 'text <i id> <A:b X:E E>', 'A:b', 'E'],
            ['*', ':e', 'text <i id> <A:B X:E E>', 'A:B', 'E'],

            ['*', 'x:*', 'text <div id x:y>', 'div', 'x:y'],
            ['*', 'x:*', 'text <DIV id x:y>', 'DIV', 'x:y'],
            ['*', 'x:*', 'text <a:b id x:y>', 'a:b', 'x:y'],
            ['*', 'x:*', 'text <A:b id x:y>', 'A:b', 'x:y'],
            ['*', 'x:*', 'text <A:B id x:y>', 'A:B', 'x:y'],
            ['*', 'x:*', 'text <div id X:Y>', 'div', 'X:Y'],
            ['*', 'x:*', 'text <DIV id X:Y>', 'DIV', 'X:Y'],
            ['*', 'x:*', 'text <a:b id X:Y>', 'a:b', 'X:Y'],
            ['*', 'x:*', 'text <A:b id X:Y>', 'A:b', 'X:Y'],
            ['*', 'x:*', 'text <A:B id X:Y>', 'A:B', 'X:Y'],

            ['*', '*:e', 'text <i id> <div id x:e>', 'div', 'x:e'],
            ['*', '*:e', 'text <i id> <DIV id x:e>', 'DIV', 'x:e'],
            ['*', '*:e', 'text <i id> <a:b id x:e>', 'a:b', 'x:e'],
            ['*', '*:e', 'text <i id> <A:b id x:e>', 'A:b', 'x:e'],
            ['*', '*:e', 'text <i id> <A:B id x:e>', 'A:B', 'x:e'],
            ['*', '*:e', 'text <i id> <div ID X:E>', 'div', 'X:E'],
            ['*', '*:e', 'text <i id> <DIV ID X:E>', 'DIV', 'X:E'],
            ['*', '*:e', 'text <i id> <a:b ID X:E>', 'a:b', 'X:E'],
            ['*', '*:e', 'text <i id> <A:b ID X:E>', 'A:b', 'X:E'],
            ['*', '*:e', 'text <i id> <A:B ID X:E>', 'A:B', 'X:E'],

            ['*', 'x:e', 'text <i e> <div x:y x:e>', 'div', 'x:e'],
            ['*', 'x:e', 'text <i e> <DIV x:y x:e>', 'DIV', 'x:e'],
            ['*', 'x:e', 'text <i e> <a:b x:y x:e>', 'a:b', 'x:e'],
            ['*', 'x:e', 'text <i e> <A:b x:y x:e>', 'A:b', 'x:e'],
            ['*', 'x:e', 'text <i e> <A:B x:y x:e>', 'A:B', 'x:e'],
            ['*', 'x:e', 'text <i e> <div X:Y X:E>', 'div', 'X:E'],
            ['*', 'x:e', 'text <i e> <DIV X:Y X:E>', 'DIV', 'X:E'],
            ['*', 'x:e', 'text <i e> <a:b X:Y X:E>', 'a:b', 'X:E'],
            ['*', 'x:e', 'text <i e> <A:b X:Y X:E>', 'A:b', 'X:E'],
            ['*', 'x:e', 'text <i e> <A:B X:Y X:E>', 'A:B', 'X:E'],

            [':*', '*', 'text <a:b id x:y> <div id>', 'div', 'id'],
            [':*', '*', 'text <a:b id x:y> <DIV id>', 'DIV', 'id'],
            [':*', '*', 'text <a:b id x:y> <div ID>', 'div', 'ID'],
            [':*', '*', 'text <a:b id x:y> <DIV ID>', 'DIV', 'ID'],
            [':*', '*', 'text <a:b id x:y> <div x:y>', 'div', 'x:y'],
            [':*', '*', 'text <a:b id x:y> <DIV x:y>', 'DIV', 'x:y'],
            [':*', '*', 'text <a:b id x:y> <div X:Y>', 'div', 'X:Y'],
            [':*', '*', 'text <a:b id x:y> <DIV X:Y>', 'DIV', 'X:Y'],

            ['*:*', '*', 'text <div id x:y> <a:b id>', 'a:b', 'id'],
            ['*:*', '*', 'text <div id x:y> <A:B id>', 'A:B', 'id'],
            ['*:*', '*', 'text <div id x:y> <a:b ID>', 'a:b', 'ID'],
            ['*:*', '*', 'text <div id x:y> <A:B ID>', 'A:B', 'ID'],
            ['*:*', '*', 'text <div id x:y> <a:b x:y>', 'a:b', 'x:y'],
            ['*:*', '*', 'text <div id x:y> <A:B x:y>', 'A:B', 'x:y'],
            ['*:*', '*', 'text <div id x:y> <a:b X:Y>', 'a:b', 'X:Y'],
            ['*:*', '*', 'text <div id x:y> <A:B X:Y>', 'A:B', 'X:Y'],

            ['div', '*', 'text <i id x:y> <div> <a:b id x:y> <div id>', 'div', 'id'],
            ['div', '*', 'text <i id x:y> <div> <a:b id x:y> <DIV id>', 'DIV', 'id'],
            ['div', '*', 'text <i id x:y> <div> <a:b id x:y> <div ID>', 'div', 'ID'],
            ['div', '*', 'text <i id x:y> <div> <a:b id x:y> <DIV ID>', 'DIV', 'ID'],
            ['div', '*', 'text <i id x:y> <div> <a:b id x:y> <div x:y>', 'div', 'x:y'],
            ['div', '*', 'text <i id x:y> <div> <a:b id x:y> <DIV x:y>', 'DIV', 'x:y'],
            ['div', '*', 'text <i id x:y> <div> <a:b id x:y> <div X:Y>', 'div', 'X:Y'],
            ['div', '*', 'text <i id x:y> <div> <a:b id x:y> <DIV X:Y>', 'DIV', 'X:Y'],

            [':div', '*', 'text <i id x:y> <div> <a:b id x:y> <div id>', 'div', 'id'],
            [':div', '*', 'text <i id x:y> <div> <a:b id x:y> <DIV id>', 'DIV', 'id'],
            [':div', '*', 'text <i id x:y> <div> <a:b id x:y> <div ID>', 'div', 'ID'],
            [':div', '*', 'text <i id x:y> <div> <a:b id x:y> <DIV ID>', 'DIV', 'ID'],
            [':div', '*', 'text <i id x:y> <div> <a:b id x:y> <div x:y>', 'div', 'x:y'],
            [':div', '*', 'text <i id x:y> <div> <a:b id x:y> <DIV x:y>', 'DIV', 'x:y'],
            [':div', '*', 'text <i id x:y> <div> <a:b id x:y> <div X:Y>', 'div', 'X:Y'],
            [':div', '*', 'text <i id x:y> <div> <a:b id x:y> <DIV X:Y>', 'DIV', 'X:Y'],

            ['a:b', '*', 'text <i id x:y> <a:b> <div id x:y> <a:b id>', 'a:b', 'id'],
            ['a:b', '*', 'text <i id x:y> <a:b> <div id x:y> <A:B id>', 'A:B', 'id'],
            ['a:b', '*', 'text <i id x:y> <a:b> <div id x:y> <a:b ID>', 'a:b', 'ID'],
            ['a:b', '*', 'text <i id x:y> <a:b> <div id x:y> <A:B ID>', 'A:B', 'ID'],
        ];
    }
}
