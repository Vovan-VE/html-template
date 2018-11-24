<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

/**
 * @since 0.4.0
 */
class PhpTempVarAssign extends PhpTempVarAccess implements PhpValueInterface
{
    public static function create(PhpTempVar $var, PhpValueInterface $value): PhpValueInterface
    {
        if ($value->isConstant()) {
            $var->setValue($value);
            return $value;
        }
        return new static($var, $value);
    }

    public function __construct(PhpTempVar $var, PhpValueInterface $value)
    {
        parent::__construct($var);

        if ($value->isConstant()) {
            throw new \LogicException('A constant value is assigning to the variable. Use `::create()` method');
        }

        $var->setValue($value);
    }

    public function getDataType(): array
    {
        return $this->getVar()->getValue()->getDataType();
    }

    public function getPhpCode(CompileScope $scope): string
    {
        $var = $this->getVar();
        $value = $var->getValue();
        $value_code = $value->getPhpCode($scope);

        if (!$var->hasReadAccess()) {
            return $value_code;
        }

        $var->setName($scope);

        return "({$var->getName()}=({$value_code}))";
    }

    public function isConstant(): bool
    {
        return false;
    }

    public function getConstValue()
    {
        throw new \LogicException('Not a constant');
    }
}
