<?php
namespace VovanVE\HtmlTemplate\tests\unit\compile;

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

    public function dataProvider()
    {
        yield from $this->expectationDataProvider(self::PATH, self::EXTENSION);
    }
}
