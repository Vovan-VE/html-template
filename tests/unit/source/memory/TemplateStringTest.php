<?php
namespace VovanVE\HtmlTemplate\tests\unit\source\memory;

use VovanVE\HtmlTemplate\source\memory\TemplateString;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class TemplateStringTest extends BaseTestCase
{
    public function testBasic()
    {
        $content = 'Lorem ipsum dolor';
        $template = new TemplateString($content, 'foobar');

        $this->assertEquals($content, $template->getContent());
        $this->assertInternalType('string', $template->getUniqueKey());
        $this->assertInternalType('string', $template->getMeta());
    }
}
