<?php
namespace VovanVE\HtmlTemplate\source;

abstract class TemplateProvider implements TemplateProviderInterface
{
    /** @var TemplateInterface[]  */
    private $templates = [];

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
        return $this->templates[$name] ?? ($this->templates[$name] = $this->fetchTemplate($name));
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
     * @return TemplateInterface
     * @throws TemplateNotFoundException
     */
    abstract protected function fetchTemplate($name): TemplateInterface;
}
