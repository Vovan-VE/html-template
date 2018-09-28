<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

abstract class PhpConst extends PhpValue
{
    public function isConstant(): bool
    {
        return true;
    }

    public function getConstValue()
    {
        return $this->getValue();
    }
}
