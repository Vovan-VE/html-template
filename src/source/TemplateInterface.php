<?php
namespace VovanVE\HtmlTemplate\source;

use VovanVE\HtmlTemplate\base\CodeFragmentInterface;

interface TemplateInterface extends CodeFragmentInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getUniqueKey(): string;

    /**
     * @return string
     * @throws TemplateNotFoundException
     * @throws TemplateReadException
     */
    public function getMeta(): string;
}
