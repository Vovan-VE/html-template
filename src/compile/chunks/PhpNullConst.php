<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpNullConst extends PhpConst
{
    public function __construct()
    {
        parent::__construct(null);
    }

    public function getValue()
    {
        return null;
    }

    public function getConstValue()
    {
        return null;
    }

    public function getPhpCode(CompileScope $scope): string
    {
        return 'null';
    }
}
