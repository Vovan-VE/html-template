<?php
namespace VovanVE\HtmlTemplate\tests\unit\components;

use VovanVE\HtmlTemplate\components\BaseComponent;
use VovanVE\HtmlTemplate\components\ComponentInterface;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class BaseComponentTest extends BaseTestCase
{
    public function testInheritance()
    {
        $o = new class extends BaseComponent {
            public function render(?\Closure $content = null): string
            {
                return '';
            }
        };

        $this->assertInstanceOf(ComponentInterface::class, $o);
    }
}
