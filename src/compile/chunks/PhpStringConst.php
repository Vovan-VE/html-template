<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

class PhpStringConst extends PhpConst
{
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public function getPhpCode(): string
    {
        return var_export($this->getValue(), true);
    }

    public function getConstValue(): string
    {
        return parent::getValue();
    }
}
