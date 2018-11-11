<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;
use VovanVE\HtmlTemplate\helpers\CompilerHelper;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;

class HtmlQuotedString implements PhpValueInterface
{
    /** @var PhpValueInterface */
    private $value;

    public function __construct(PhpValueInterface $value)
    {
        $this->value = $value;
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        return [DataTypes::T_STRING, DataTypes::STR_HTML];
    }

    public function getPhpCode(CompileScope $scope): string
    {
        $quot = '\'"\'';
        /** @uses RuntimeHelperInterface::htmlEncode() */
        return "($quot.\$runtime::htmlEncode({$this->value->getPhpCode($scope)}).$quot)";
    }

    public function isConstant(): bool
    {
        return $this->value->isConstant();
    }

    public function getConstValue()
    {
        return '"' . CompilerHelper::htmlEncode($this->value->getConstValue()) . '"';
    }
}
