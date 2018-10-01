<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpStringConst extends PhpConst
{
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public function getPhpCode(CompileScope $scope): string
    {
        return var_export($this->getValue(), true);
    }

    public function getConstValue(): string
    {
        return parent::getValue();
    }
}
