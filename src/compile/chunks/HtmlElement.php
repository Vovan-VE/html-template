<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\runtime\RuntimeHelper;

class HtmlElement extends Element implements PhpValueInterface
{
    /** @var bool */
    private $isConst;

    public function __construct(string $name, PhpArray $attributes, ?NodesList $content = null)
    {
        parent::__construct($name, $attributes, $content);

        $this->isConst = $attributes->isConstant() && (!$content || $content->isConstant());
    }

    public function getPhpCode(): string
    {
        /** @uses RuntimeHelperInterface::createElement() */
        return "(\$runtime::createElement({$this->getArgumentsCode()}))";
    }

    public function isConstant(): bool
    {
        return $this->isConst;
    }

    public function getConstValue(): string
    {
        $content = $this->getContent();
        return RuntimeHelper::createElement(
            $this->getName(),
            $this->getAttributes()->getConstValue(),
            $content
                ? $content->getConstValue()
                : null
        );
    }
}
