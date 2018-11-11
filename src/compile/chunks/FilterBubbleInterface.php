<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

/**
 * Interface FilterBubbleInterface
 * @package VovanVE\HtmlTemplate
 * @since 0.4.0
 */
interface FilterBubbleInterface
{
    public function bubbleFilter(BaseFilter $filter): ?PhpValueInterface;
}
