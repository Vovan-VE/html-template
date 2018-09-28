<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

class ComponentElement extends Element implements PhpValueInterface
{
    public function getPhpCode(): string
    {
        /** @uses RuntimeHelperInterface::createComponent() */
        return "(\$runtime->createComponent({$this->getArgumentsCode()}))";
    }

    public function isConstant(): bool
    {
        return false;
    }

    public function getConstValue()
    {
        throw new \LogicException('Not a constant');
    }
}
