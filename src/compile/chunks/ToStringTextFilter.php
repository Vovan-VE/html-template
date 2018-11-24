<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

/**
 * @since 0.4.0
 */
class ToStringTextFilter extends ToStringFilter
{
    public static function create(PhpValueInterface $value): PhpValueInterface
    {
        $result = parent::create($value);
        if ($result !== $value) {
            return $result;
        }

        $result = new static($value);
        if ($value instanceof FilterBubbleInterface) {
            return $value->bubbleFilter($result) ?: $result;
        }
        return $result;
    }
}
