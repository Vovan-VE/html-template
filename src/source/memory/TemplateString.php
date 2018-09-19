<?php
namespace VovanVE\HtmlTemplate\source\memory;

use VovanVE\HtmlTemplate\source\Template;

class TemplateString extends Template
{
    /**
     * @param string $content
     * @param string $name
     */
    public function __construct(string $content, string $name)
    {
        parent::__construct($name, md5($content));
        $this->content = $content;
    }

    /**
     * @return string
     */
    protected function fetchContent(): string
    {
        throw new \LogicException('Unused');
    }

    /**
     * @return string
     */
    public function getMeta(): string
    {
        return "content-hash: {$this->getUniqueKey()}\n";
    }
}
