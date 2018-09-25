<?php
namespace VovanVE\HtmlTemplate\source;

abstract class TemplateProvider implements TemplateProviderInterface
{
    /** @var TemplateInterface[]|bool[]  */
    protected $templates = [];

    /**
     */
    public function __construct()
    {
    }

    /**
     * @param string $name
     * @return TemplateInterface
     * @throws TemplateNotFoundException
     */
    public function getTemplate(string $name): TemplateInterface
    {
        $template = $this->templates[$name] ?? ($this->templates[$name] = $this->fetchTemplate($name) ?? false);
        if (false === $template) {
            throw new TemplateNotFoundException('Template file was not found');
        }
        return $template;
    }

    /**
     * @return $this
     */
    public function clear(): self
    {
        $this->templates = [];
        return $this;
    }

    /**
     * @param string $name
     * @return TemplateInterface|null
     */
    abstract protected function fetchTemplate(string $name): ?TemplateInterface;
}
