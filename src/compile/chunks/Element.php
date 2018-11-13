<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class Element
{
    /** @var string */
    private $name;
    /** @var PhpArray */
    private $attributes;
    /** @var PhpValueInterface|null */
    private $content;

    public function __construct(string $name, PhpArray $attributes, ?NodesList $content = null)
    {
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

    public function getContent(): ?PhpValueInterface
    {
        return $this->content;
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
