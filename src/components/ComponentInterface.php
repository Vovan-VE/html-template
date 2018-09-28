<?php
namespace VovanVE\HtmlTemplate\components;

/**
 * Component to render custom markup
 * @since 0.1.0
 */
interface ComponentInterface
{
    /**
     * @param \Closure|null $content
     * @return string
     */
    public function render(?\Closure $content = null): string;
}
