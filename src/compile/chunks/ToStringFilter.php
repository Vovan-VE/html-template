<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;

/**
 * Class ToStringFilter
 * @package VovanVE\HtmlTemplate
 * @since 0.4.0
 */
class ToStringFilter extends BaseFilter
{
    public static function create(PhpValueInterface $value): PhpValueInterface
    {
        $type = $value->getDataType()[0] ?? null;
        if (DataTypes::T_BOOL === $type || DataTypes::T_NULL === $type) {
            return new PhpStringConst('', DataTypes::STR_TEXT);
        }
        if (DataTypes::T_STRING === $type) {
            return $value;
        }

        return parent::create($value);
    }

    public function getDataType(): array
    {
        return [DataTypes::T_STRING, DataTypes::STR_TEXT];
    }

    public function getPhpCode(CompileScope $scope): string
    {
        $code = $this->value->getPhpCode($scope);

        if ($this->isConstant() && $this->getConstValue() === $this->value->getConstValue()) {
            return $code;
        }

        $type = $this->value->getDataType()[0] ?? null;
        if (DataTypes::T_STRING === $type) {
            return $code;
        }

        /** @uses RuntimeHelperInterface::toString() */
        return "(\$runtime::toString($code))";
    }

    public function getConstValue()
    {
        return RuntimeHelper::toString($this->value->getConstValue());
    }
}
