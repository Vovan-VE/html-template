<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

class ComponentElement extends Element implements PhpValueInterface
{
    public function getPhpCode(): string
    {
        $arguments = $this->getArgumentsCode();
        if (isset($arguments[2])) {
            $arguments[2] = "function()use(\$runtime){return {$arguments[2]};}";
        }

        $arguments = join(',', $arguments);
        /** @uses RuntimeHelperInterface::createComponent() */
        return "(\$runtime->createComponent($arguments))";
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
