<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

abstract class Element extends PhpValue
{
    /** @var string */
    private $name;
    /** @var PhpArray */
    private $attributes;
    /** @var PhpValue|null */
    private $content;

    public function __construct(string $name, PhpArray $attributes, ?NodesList $content = null)
    {
        parent::__construct();

        $this->name = $name;
        $this->attributes = $attributes;
        $this->content = $content
            ? ($content->getValues()
                ? new PhpConcatenation(DataTypes::STR_HTML, ...$content->getValues())
                : new PhpStringConst('', DataTypes::STR_HTML)
            )
            : null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAttributes(): PhpArray
    {
        return $this->attributes;
    }

    public function getContent(): ?PhpValue
    {
        return $this->content;
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
     * @return string[]
     */
    protected function getArgumentsCode(CompileScope $scope): array
    {
        $arguments = [(new PhpStringConst($this->getName()))->getPhpCode($scope)];

        $attributes = $this->getAttributes();
        $content = $this->getContent();

        // $name, $attrs, $content
        // $name, $attrs
        // $name
        if ($content || !$attributes->isEmpty()) {
            $arguments[] = $attributes->getPhpCode($scope);
        }
        if ($content) {
            $arguments[] = $content->getPhpCode($scope);
        }
        return $arguments;
    }
}
