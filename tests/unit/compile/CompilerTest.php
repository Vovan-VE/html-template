<?php
namespace VovanVE\HtmlTemplate\tests\unit\compile;

use VovanVE\HtmlTemplate\compile\Compiler;
use VovanVE\HtmlTemplate\report\ReportInterface;
use VovanVE\HtmlTemplate\source\Template;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class CompilerTest extends BaseTestCase
{
    /**
     * @return Compiler
     */
    public function testCreate(): Compiler
    {
        $this->expectNotToPerformAssertions();
        return new Compiler();
    }

    /**
     * @param string $source
     * @param string $expected
     * @param Compiler $compiler
     * @dataProvider provideSuccessCompilations
     * @depends testCreate
     */
    public function testCompile($source, $expected, $compiler)
    {
        $template = new class($source) extends Template {
            public function __construct($content)
            {
                $key = strlen($content) < 32
                    ? $content
                    : md5($content);
                parent::__construct($key, $key);
                $this->content = $content;
            }

            protected function fetchContent(): string
            {
                throw new \LogicException('Unused');
            }

            public function getMeta(): string
            {
                return $this->content;
            }
        };

        $report = $compiler->syntaxCheck($template);

        $this->assertInstanceOf(ReportInterface::class, $report);
        $this->assertTrue($report->isSuccess(), 'syntax check status');

        /** @noinspection PhpUnhandledExceptionInspection */
        $result = $compiler->compile($template);
        $this->assertEquals($expected, $result->getContent(), 'compiled code');
    }

    public function provideSuccessCompilations()
    {
        yield [
            'lorem <div id=foo  title={{ $label }}>ipsum {{ $content }} dolor</div>sit amet',
            'lorem <div id="foo" title="<' . '?= $runtime::htmlEncode(($runtime->param(\'label\')), \'UTF-8\') ?' . '>">ipsum <' . '?= ($runtime->param(\'content\')) ?' . '> dolor</div>sit amet',
        ];
    }
}
