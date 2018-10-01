<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpNot implements PhpValueInterface
{
    /** @var PhpValueInterface */
    private $value;

    public static function create(PhpValueInterface $value): PhpNot
    {
        // if $value is not($inner) && $inner is not(...)
        // then return $inner
        // since not($value) === not(not($inner))
        if ($value instanceof self && $value->value instanceof self) {
            return $value->value;
        }
        return new self($value);
    }

    public function __construct(PhpValueInterface $value)
    {
        $this->value = $value;
    }

    public function getPhpCode(CompileScope $scope): string
    {
        $not = true;
        $inner = $this->value;
        while ($inner instanceof self) {
            $not = !$not;
            $inner = $inner->value;
        }

        $code = $inner->getPhpCode($scope);

        if ($not) {
            return "(!($code))";
        }
        return "((bool)($code))";
    }

    public function isConstant(): bool
    {
        return $this->value->isConstant();
    }

    public function getConstValue(): bool
    {
        return !$this->value->getConstValue();
    }
}
