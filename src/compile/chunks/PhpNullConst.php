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

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        return [DataTypes::T_NULL];
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
