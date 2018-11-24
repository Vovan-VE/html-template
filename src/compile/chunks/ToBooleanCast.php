<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

/**
 * @since 0.4.0
 */
class ToBooleanCast implements PhpValueInterface
{
    /** @var PhpValueInterface */
    private $value;

    public static function create(PhpValueInterface $value): PhpValueInterface
    {
        if ($value instanceof self) {
            return $value;
        }
        if ($value instanceof HtmlElement) {
            return new PhpBoolConst(true);
        }
        if ($value->isConstant()) {
            return new PhpBoolConst((bool)$value->getConstValue());
        }
        if ($value->getDataType() === [DataTypes::T_BOOL]) {
            return $value;
        }
        return new self($value);
    }

    public function __construct(PhpValueInterface $value)
    {
        $this->value = $value;
    }

    public function getValue(): PhpValueInterface
    {
        return $this->value;
    }

    public function getDataType(): array
    {
        return [DataTypes::T_BOOL];
    }

    public function getPhpCode(CompileScope $scope): string
    {
        return "((bool)({$this->value->getPhpCode($scope)}))";
    }

    public function isConstant(): bool
    {
        return $this->value->isConstant();
    }

    public function getConstValue(): bool
    {
        return $this->value->getConstValue();
    }
}
