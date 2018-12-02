<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

/**
 * @since 0.4.0
 */
class PhpTempVarRead extends PhpTempVarAccess
{
    public static function create(PhpTempVar $var): PhpValue
    {
        $value = $var->getValue();
        if ($value->isConstant()) {
            return $value;
        }

        return new static($var);
    }

    public function __construct(PhpTempVar $var)
    {
        parent::__construct($var);

        if ($var->getValue()->isConstant()) {
            throw new \LogicException('A constant value was assigned to the variable. Use `::create()` method');
        }

        $var->addReadAccess();
    }

    public function __destruct()
    {
        $this->getVar()->removeReadAccess();
        parent::__destruct();
    }

    public function getDataType(): array
    {
        return $this->getVar()->getValue()->getDataType();
    }

    public function getPhpCode(CompileScope $scope): string
    {
        $var = $this->getVar();

        return "({$var->getName()})";
    }
}
