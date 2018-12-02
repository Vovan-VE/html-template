<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpNot extends BaseFilter
{
    public static function create(PhpValue $value): PhpValue
    {
        // if $value is not($inner)
        // then return bool($inner)
        // since not($value) === bool($inner)
        if ($value instanceof self) {
            return ToBooleanCast::create($value->value);
        }

        $v = $value;
        // if $value is bool($inner)
        // use $inner
        if ($v instanceof ToBooleanCast) {
            $v = $v->getValue();
        }

        if ($v instanceof HtmlElement) {
            return new PhpBoolConst(false);
        }
        if ($v->isConstant()) {
            return new PhpBoolConst(!$v->getConstValue());
        }
        return parent::create($v);
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        return [DataTypes::T_BOOL];
    }

    public function getPhpCode(CompileScope $scope): string
    {
        return "(!({$this->value->getPhpCode($scope)}))";
    }

    public function getConstValue(): bool
    {
        return !$this->value->getConstValue();
    }
}
