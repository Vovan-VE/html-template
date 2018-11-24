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

    public static function willSinkInto(PhpValueInterface $value): bool
    {
        // $value = X
        // f($value) = f(X)
        // vs
        // $value = g(X)
        // f($value) = g(f(X))
        $filtered = static::create($value);
        return !($filtered instanceof static && $filtered->value === $value);
    }

    public static function willSinkIntoAny(PhpValueInterface ...$values): bool
    {
        foreach ($values as $value) {
            if (static::willSinkInto($value)) {
                return true;
            }
        }
        return false;
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
