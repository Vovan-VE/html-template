<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

abstract class PhpConst extends PhpValue
{
    private $value;

    public function __construct($value)
    {
        parent::__construct();
        $this->value = $value;
    }

    /**
     * @return mixed
     * @since 0.4.0
     */
    public function getValue()
    {
        return $this->value;
    }

    public function isConstant(): bool
    {
        return true;
    }

    public function getConstValue()
    {
        return $this->getValue();
    }
}
