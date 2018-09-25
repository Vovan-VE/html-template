<?php
namespace VovanVE\HtmlTemplate\components;

/**
 * Component to render custom markup
 * @since 0.1.0
 */
interface ComponentInterface
{
    /**
     * @param array|null $content
     * @return string
     */
    public function render(?array $content = null): string;
}
