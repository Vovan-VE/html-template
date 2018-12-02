<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpBoolConst extends PhpConst
{
    public function __construct(bool $value)
    {
        parent::__construct($value);
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        return [DataTypes::T_BOOL];
    }

    public function getValue(): bool
    {
        return parent::getValue();
    }

    public function getPhpCode(CompileScope $scope): string
    {
        return parent::getValue() ? 'true' : 'false';
    }
}
