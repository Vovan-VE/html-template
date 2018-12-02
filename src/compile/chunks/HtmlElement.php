<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;

class HtmlElement extends Element
{
    /** @var bool */
    private $isConst;

    /**
     * @param string $name
     * @param PhpArray $attributes
     * @param NodesList|null $content
     * @return PhpValue
     * @since 0.4.0
     */
    public static function create(string $name, PhpArray $attributes, ?NodesList $content = null): PhpValue
    {
        if ($attributes->isConstant()) {
            if (null === $content || $content->isConstant()) {
                $const = RuntimeHelper::createElement(
                    $name,
                    $attributes->getConstValue(),
                    $content
                        ? join('', $content->getConstValue())
                        : null
                );
                return new PhpStringConst($const, DataTypes::STR_HTML);
            }

            $const = RuntimeHelper::createElement($name, $attributes->getConstValue(), '</>');
            [$start, $end] = explode('</>', $const);
            return new PhpConcatenation(
                DataTypes::STR_HTML,
                new PhpStringConst($start, DataTypes::STR_HTML),
                ...$content
                    ->append(new PhpStringConst($end, DataTypes::STR_HTML))
                    ->getValues()
            );
        }

        return new static($name, $attributes, $content);
    }

    public function __construct(string $name, PhpArray $attributes, ?NodesList $content = null)
    {
        parent::__construct($name, $attributes, $content);

        $this->isConst = $attributes->isConstant() && (!$content || $content->isConstant());
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
