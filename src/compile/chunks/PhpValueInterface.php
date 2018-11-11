<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

interface PhpValueInterface
{
    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array;

    public function getPhpCode(CompileScope $scope): string;

    public function isConstant(): bool;

    public function getConstValue();
}
