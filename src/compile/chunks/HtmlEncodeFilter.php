<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;

/**
 * Class HtmlEncodeFilter
 * @package VovanVE\HtmlTemplate
 * @since 0.4.0
 */
class HtmlEncodeFilter extends BaseFilter
{
    public static function create(PhpValue $value): PhpValue
    {
        $full_type = $value->getDataType();

        if ([DataTypes::T_STRING, DataTypes::STR_HTML] === $full_type) {
            return $value;
        }

        return parent::create($value);
    }

    public function __construct(PhpValue $value)
    {
        if (DataTypes::T_STRING !== ($value->getDataType()[0] ?? null)) {
            throw new \LogicException('Expected string data type');
        }

        parent::__construct($value);
    }

    public function getDataType(): array
    {
        return [DataTypes::T_STRING, DataTypes::STR_HTML];
    }

    public function getPhpCode(CompileScope $scope): string
    {
        $code = $this->value->getPhpCode($scope);

        if ($this->isConstant()) {
            $const_value = $this->getConstValue();
            if ($const_value === $this->value->getConstValue()) {
                return $code;
            }
            return (new PhpStringConst($const_value))->getPhpCode($scope);
        }

        if ($this->value->getDataType() === $this->getDataType()) {
            return $code;
        }

        /** @uses RuntimeHelperInterface::htmlEncode() */
        return "(\$runtime::htmlEncode({$code}))";
    }

    public function getConstValue()
    {
        $value = $this->value->getConstValue();

        if ($this->value->getDataType() === $this->getDataType()) {
            return $value;
        }

        return RuntimeHelper::htmlEncode($value);
    }
}
