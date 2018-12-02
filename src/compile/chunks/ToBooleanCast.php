<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

/**
 * @since 0.4.0
 */
class ToBooleanCast extends BaseFilter
{
    public static function create(PhpValue $value): PhpValue
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
        return parent::create($value);
    }

    public function getValue(): PhpValue
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

    public function getConstValue(): bool
    {
        return $this->value->getConstValue();
    }
}
