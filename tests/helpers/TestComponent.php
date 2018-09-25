<?php
namespace VovanVE\HtmlTemplate\tests\helpers;

use VovanVE\HtmlTemplate\components\BaseComponent;

class TestComponent extends BaseComponent
{
    public $foo;
    public $bar;

    /**
     * @param array|null $content
     * @return string
     */
    public function render(?array $content = null): string
    {
        $head = "Test Component";
        $props = "foo=" . json_encode($this->foo) . " bar=" . json_encode($this->bar);

        if (null === $content) {
            return "<!-- $head: $props /-->";
        }
        return "<!-- $head: $props -->" . join('', $content) . "<!-- /$head -->";
    }
}
