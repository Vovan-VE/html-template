<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

/**
 * Class BaseFilter
 * @package VovanVE\HtmlTemplate
 * @since 0.4.0
 */
abstract class BaseFilter implements PhpValueInterface
{
    /** @var PhpValueInterface */
    protected $value;

    public static function create(PhpValueInterface $value): PhpValueInterface
    {
        $result = new static($value);
        if ($value instanceof FilterBubbleInterface) {
            return $value->bubbleFilter($result) ?: $result;
        }
        return $result;
    }

    public function __construct(PhpValueInterface $value)
    {
        $this->value = $value;
    }

    public function isConstant(): bool
    {
        return $this->value->isConstant();
    }
}
