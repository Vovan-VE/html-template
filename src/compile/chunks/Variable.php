<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class Variable implements PhpValueInterface
{
    /** * @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        return [];
    }

    public function getPhpCode(CompileScope $scope): string
    {
        /** @uses RuntimeHelperInterface::param() */
        return '($runtime->param(' . var_export($this->name, true) . '))';
    }

    public function isConstant(): bool
    {
        return false;
    }

    public function getConstValue()
    {
        throw new \RuntimeException('Not a constant');
    }
}
