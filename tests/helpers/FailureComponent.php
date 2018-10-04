<?php
namespace VovanVE\HtmlTemplate\tests\helpers;

use VovanVE\HtmlTemplate\components\BaseComponent;
use VovanVE\HtmlTemplate\components\ComponentTraceException;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;

class FailureComponent extends BaseComponent
{
    public $message;

    /**
     * @param RuntimeHelperInterface $runtime
     * @param \Closure|null $content
     * @return string
     * @throws ComponentTraceException
     */
    public function render(RuntimeHelperInterface $runtime, ?\Closure $content = null): string
    {
        throw new \RuntimeException($this->message ?: 'Unknown test failure');
    }
}
