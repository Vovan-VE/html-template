<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

class PhpBoolConst extends PhpConst
{
    public function __construct(bool $value)
    {
        parent::__construct($value);
    }

    public function getValue(): bool
    {
        return parent::getValue();
    }

    public function getConstValue(): bool
    {
        return parent::getValue();
    }

    public function getPhpCode(): string
    {
        return parent::getValue() ? 'true' : 'false';
    }
}
