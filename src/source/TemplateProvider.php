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
    public function getTemplate($name): TemplateInterface
    {
        $template = $this->templates[$name] ?? ($this->templates[$name] = $this->fetchTemplate($name) ?? false);
        if (false === $template) {
            throw new TemplateNotFoundException('Template file was not found');
        }
        return $template;
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->templates = [];
    }

    /**
     * @param string $name
     * @return TemplateInterface|null
     */
    abstract protected function fetchTemplate($name): ?TemplateInterface;
}
