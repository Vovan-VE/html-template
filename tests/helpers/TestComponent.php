<?php
namespace VovanVE\HtmlTemplate\tests\helpers;

use VovanVE\HtmlTemplate\components\BaseComponent;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;

class TestComponent extends BaseComponent
{
    public $foo;
    public $bar;

    /**
     * @param RuntimeHelperInterface $runtime
     * @param \Closure|null $content
     * @return string
     */
    public function render(RuntimeHelperInterface $runtime, ?\Closure $content = null): string
    {
        $head = "Test Component";
        $props = "foo=" . json_encode($this->foo) . " bar=" . json_encode($this->bar);

        if (null === $content) {
            return "<!-- $head: $props /-->";
        }
        return "<!-- $head: $props -->" . $content($runtime) . "<!-- /$head -->";
    }
}
