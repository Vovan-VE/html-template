<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;
use VovanVE\HtmlTemplate\helpers\CompilerHelper;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;

class HtmlQuotedString extends PhpStringConst
{
    public function __construct(string $value)
    {
        parent::__construct($value, DataTypes::STR_HTML);
    }

    public function getPhpCode(CompileScope $scope): string
    {
        $code = parent::getPhpCode($scope);
        $quot = "'\"'";
        /** @uses RuntimeHelperInterface::htmlEncode() */
        return "($quot.\$runtime::htmlEncode($code).$quot)";
    }

    public function getConstValue()
    {
        return '"' . CompilerHelper::htmlEncode($this->getValue()) . '"';
    }
}
