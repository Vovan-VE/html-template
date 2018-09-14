<?php
namespace VovanVE\HtmlTemplate\tests\unit\compile;

use VovanVE\HtmlTemplate\compile\Compiler;
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
     * @param Compiler $compiler
     * @depends testCreate
     */
    public function testCompile($compiler)
    {

    }

    /**
     * @param Compiler $compiler
     * @depends testCreate
     */
    public function testSyntaxCheck($compiler)
    {

    }
}
