<?php
namespace VovanVE\HtmlTemplate\source\memory;

use VovanVE\HtmlTemplate\source\TemplateInterface;
use VovanVE\HtmlTemplate\source\TemplateProvider;

class TemplateStringProvider extends TemplateProvider
{
    /**
     * @param string $name
     * @param string $content
     * @return $this
     */
    public function setTemplate($name, $content): self
    {
        $this->templates[$name] = new TemplateString($content, $name);
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function deleteTemplate($name): self
    {
        unset($this->templates[$name]);
        return $this;
    }

    /**
     * @param string $name
     * @return TemplateInterface|null
     */
    protected function fetchTemplate($name): ?TemplateInterface
    {
        return null;
    }
}
