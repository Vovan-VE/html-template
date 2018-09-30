<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

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

    public function getPhpCode(): string
    {
        $quot = '\'"\'';
        /** @uses RuntimeHelperInterface::htmlEncode() */
        return "($quot.\$runtime::htmlEncode({$this->value->getPhpCode()}).$quot)";
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
