<?php
namespace VovanVE\HtmlTemplate\tests\helpers;

use VovanVE\HtmlTemplate\components\BaseComponent;

class TestComponent extends BaseComponent
{
    public $foo;
    public $bar;

    /**
     * @param \Closure|null $content
     * @return string
     */
    public function render(?\Closure $content = null): string
    {
        $head = "Test Component";
        $props = "foo=" . json_encode($this->foo) . " bar=" . json_encode($this->bar);

        if (null === $content) {
            return "<!-- $head: $props /-->";
        }
        return "<!-- $head: $props -->" . join('', $content()) . "<!-- /$head -->";
    }
}
