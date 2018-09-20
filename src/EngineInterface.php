<?php
namespace VovanVE\HtmlTemplate;

use VovanVE\HtmlTemplate\caching\CachedEntryInterface;
use VovanVE\HtmlTemplate\caching\CacheInterface;
use VovanVE\HtmlTemplate\compile\CompileException;
use VovanVE\HtmlTemplate\compile\CompilerInterface;
use VovanVE\HtmlTemplate\report\ReportInterface;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;
use VovanVE\HtmlTemplate\source\TemplateNotFoundException;
use VovanVE\HtmlTemplate\source\TemplateProviderInterface;
use VovanVE\HtmlTemplate\source\TemplateReadException;

interface EngineInterface
{
    /**
     * @return TemplateProviderInterface
     */
    public function getTemplateProvider(): ?TemplateProviderInterface;

    /**
     * @param TemplateProviderInterface|null $provider
     * @return $this
     */
    public function setTemplateProvider(?TemplateProviderInterface $provider): self;

    /**
     * @return CacheInterface
     */
    public function getCache(): ?CacheInterface;

    /**
     * @param CacheInterface|null $cache
     * @return $this
     */
    public function setCache(?CacheInterface $cache): self;

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): ?CompilerInterface;

    /**
     * @param CompilerInterface|null $compiler
     * @return $this
     */
    public function setCompiler(?CompilerInterface $compiler): self;

    /**
     * @param string $name
     * @return CachedEntryInterface
     * @throws ConfigException
     * @throws CompileException
     * @throws TemplateNotFoundException
     * @throws TemplateReadException
     */
    public function compileTemplate(string $name): CachedEntryInterface;

    /**
     * @param string $name
     * @return ReportInterface
     * @throws ConfigException
     * @throws CompileException
     * @throws TemplateNotFoundException
     * @throws TemplateReadException
     * @since 0.1.0
     */
    public function checkTemplateSyntax(string $name): ReportInterface;

    /**
     * @param string $name
     * @param RuntimeHelperInterface|null $runtime
     * @throws ConfigException
     */
    public function runTemplate(string $name, ?RuntimeHelperInterface $runtime = null): void;
}
