<?php
namespace VovanVE\HtmlTemplate\components;

use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;

/**
 * Component to render custom markup
 * @since 0.1.0
 */
interface ComponentInterface
{
    /**
     * @param RuntimeHelperInterface $runtime
     * @param \Closure|null $content
     * @return string
     * @throws ComponentTraceException
     */
    public function render(RuntimeHelperInterface $runtime, ?\Closure $content = null): string;
}
