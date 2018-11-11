<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpStringConst extends PhpConst
{
    /** @var string */
    private $subtype;

    public function __construct(string $value, ?string $subtype = DataTypes::STR_TEXT)
    {
        parent::__construct($value);
        $this->subtype = $subtype;
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        return [DataTypes::T_STRING, $this->subtype];
    }

    public function getPhpCode(CompileScope $scope): string
    {
        return var_export($this->getValue(), true);
    }

    public function getConstValue(): string
    {
        return parent::getValue();
    }
}
