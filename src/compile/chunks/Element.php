<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class Element
{
    /** @var string */
    private $name;
    /** @var PhpArray */
    private $attributes;
    /** @var NodesList|null */
    private $content;

    public function __construct(string $name, PhpArray $attributes, ?NodesList $content = null)
    {
        $this->name = $name;
        $this->attributes = $attributes;
        $this->content = $content;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAttributes(): PhpArray
    {
        return $this->attributes;
    }

    public function getContent(): ?NodesList
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
