<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

/**
 * Class BaseFilter
 * @package VovanVE\HtmlTemplate
 * @since 0.4.0
 */
abstract class BaseFilter extends PhpValue
{
    /** @var PhpValue */
    protected $value;

    public static function create(PhpValue $value): PhpValue
    {
        $result = new static($value);
        if ($value instanceof FilterBubbleInterface) {
            return $value->bubbleFilter($result) ?: $result;
        }
        return $result;
    }

    public static function willSinkInto(PhpValue $value): bool
    {
        // $value = X
        // f($value) = f(X)
        // vs
        // $value = g(X)
        // f($value) = g(f(X))
        $filtered = static::create($value);
        return !($filtered instanceof static && $filtered->value === $value);
    }

    public static function willSinkIntoAny(PhpValue ...$values): bool
    {
        foreach ($values as $value) {
            if (static::willSinkInto($value)) {
                return true;
            }
        }
        return false;
    }

    public function __construct(PhpValue $value)
    {
        parent::__construct();
        $this->value = $value;
    }

    public function isConstant(): bool
    {
        return $this->value->isConstant();
    }
}
