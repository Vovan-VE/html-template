<?php
namespace VovanVE\HtmlTemplate\tests\helpers;

use VovanVE\HtmlTemplate\components\BaseComponent;

class CounterStepComponent extends BaseComponent
{
    public $step;

    /**
     * @param array|null $content
     * @return string
     */
    public function render(?array $content = null): string
    {
        $marker = "step: " . ($this->step ?? '?');
        if (null === $content) {
            return "<!-- $marker /-->";
        }
        return "<!-- $marker -->" . join('', $content) . "<!-- /$marker -->";
    }
}
