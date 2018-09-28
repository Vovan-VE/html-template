<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

interface PhpValueInterface
{
    public function getPhpCode(): string;

    public function isConstant(): bool;

    public function getConstValue();
}
