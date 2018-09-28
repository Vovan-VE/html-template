<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

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
     * @return string
     */
    protected function getArgumentsCode(): string
    {
        $arguments = (new PhpStringConst($this->getName()))->getPhpCode();

        $attributes = $this->getAttributes();
        $content = $this->getContent();

        // $name, $attrs, $content
        // $name, $attrs
        // $name
        if ($content || !$attributes->isEmpty()) {
            $arguments .= ',' . $attributes->getPhpCode();
        }
        if ($content) {
            $arguments .= ',' . $content->getPhpCode();
        }
        return $arguments;
    }
}
