<?php
namespace VovanVE\HtmlTemplate\tests\unit\source;

use VovanVE\HtmlTemplate\source\Template;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class TemplateTest extends BaseTestCase
{
    public function testBasic()
    {
        $name = 'foo`~!@#$%^&*()-_=+\\|[]{};:\'",.<>/?bar';

        $template = new class($name) extends Template {
            protected function fetchContent(): string
            {
                return '';
            }

            public function getMeta(): string
            {
                return '';
            }
        };

        $this->assertEquals($name, $template->getName(), 'name');
        $this->assertEquals($name, $template->getUniqueKey(), 'key');
    }
}
