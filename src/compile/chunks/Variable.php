<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

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

    public function getPhpCode(): string
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
