<?php
namespace VovanVE\HtmlTemplate\compile;

use VovanVE\HtmlTemplate\report\ReportInterface;
use VovanVE\HtmlTemplate\source\TemplateInterface;

interface CompilerInterface
{
    /**
     * @return string
     * @since 0.1.0
     */
    public function getMeta(): string;

    /**
     * @param TemplateInterface $template
     * @return CompiledEntryInterface
     * @throws SyntaxException
     */
    public function compile(TemplateInterface $template): CompiledEntryInterface;

    /**
     * @param TemplateInterface $template
     * @return ReportInterface
     */
    public function syntaxCheck(TemplateInterface $template): ReportInterface;
}
