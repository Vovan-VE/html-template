<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

abstract class PhpValue
{
    public function __construct()
    {
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        return [];
    }

    abstract public function getPhpCode(CompileScope $scope): string;

    public function isConstant(): bool
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getConstValue()
    {
        throw new \LogicException('Not a constant');
    }
}
