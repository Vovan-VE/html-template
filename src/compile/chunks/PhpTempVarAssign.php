<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

/**
 * @since 0.4.0
 */
class PhpTempVarAssign extends PhpTempVarAccess
{
    public static function create(PhpTempVar $var, PhpValue $value): PhpValue
    {
        if ($value->isConstant()) {
            $var->setValue($value);
            return $value;
        }
        return new static($var, $value);
    }

    public function __construct(PhpTempVar $var, PhpValue $value)
    {
        parent::__construct($var);

        if ($value->isConstant()) {
            throw new \LogicException('A constant value is assigning to the variable. Use `::create()` method');
        }

        $var->setValue($value);
    }

    /**
     * @return PhpValue|static
     */
    public function finalize(): PhpValue
    {
        $var = $this->getVar();
        $value = $var->getValue();

        if (!$var->hasReadAccess()) {
            return $value->finalize();
        }

        $new_var = $var->finalize();
        return new static($new_var, $new_var->getValue());
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
}
