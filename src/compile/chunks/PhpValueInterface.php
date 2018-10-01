<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

interface PhpValueInterface
{
    public function getPhpCode(CompileScope $scope): string;

    public function isConstant(): bool;

    public function getConstValue();
}
