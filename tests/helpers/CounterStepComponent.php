<?php
namespace VovanVE\HtmlTemplate\tests\helpers;

use VovanVE\HtmlTemplate\components\BaseComponent;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;

class CounterStepComponent extends BaseComponent
{
    public $step;

    /**
     * @param RuntimeHelperInterface $runtime
     * @param \Closure|null $content
     * @return string
     */
    public function render(RuntimeHelperInterface $runtime, ?\Closure $content = null): string
    {
        $marker = "step: " . ($this->step ?? '?');
        if (null === $content) {
            return "<!-- $marker /-->";
        }
        return "<!-- $marker -->" . join('', $content($runtime)) . "<!-- /$marker -->";
    }
}
