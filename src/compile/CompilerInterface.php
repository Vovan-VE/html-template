<?php
namespace VovanVE\HtmlTemplate\compile;

use VovanVE\HtmlTemplate\report\ReportInterface;
use VovanVE\HtmlTemplate\source\TemplateInterface;

interface CompilerInterface
{
    /**
     * @param TemplateInterface $template
     * @return CompiledEntryInterface
     * @throws CompileException
     */
    public function compile($template): CompiledEntryInterface;

    /**
     * @param TemplateInterface $template
     * @return ReportInterface
     */
    public function syntaxCheck($template): ReportInterface;
}
