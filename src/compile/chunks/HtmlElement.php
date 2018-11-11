<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;
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

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        return [DataTypes::T_STRING, DataTypes::STR_HTML];
    }

    /**
     * @param CompileScope $scope
     * @return string
     */
    public function getPhpCode(CompileScope $scope): string
    {
        $arguments = join(',', $this->getArgumentsCode($scope));
        /** @uses RuntimeHelperInterface::createElement() */
        return "(\$runtime::createElement($arguments))";
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
