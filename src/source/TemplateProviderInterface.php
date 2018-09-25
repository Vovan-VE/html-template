<?php
namespace VovanVE\HtmlTemplate\source;

interface TemplateProviderInterface
{
    /**
     * @param string $name
     * @return TemplateInterface
     * @throws TemplateNotFoundException
     */
    public function getTemplate(string $name): TemplateInterface;
}
