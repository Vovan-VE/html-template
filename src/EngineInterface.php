<?php
namespace VovanVE\HtmlTemplate;

use VovanVE\HtmlTemplate\caching\CachedEntryInterface;
use VovanVE\HtmlTemplate\caching\CacheInterface;
use VovanVE\HtmlTemplate\compile\CompileException;
use VovanVE\HtmlTemplate\compile\CompilerInterface;
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
     * @throws ConfigException
     */
    public function setTemplateProvider($provider): self;

    /**
     * @return CacheInterface
     */
    public function getCache(): ?CacheInterface;

    /**
     * @param CacheInterface|null $cache
     * @return $this
     * @throws ConfigException
    */
    public function setCache($cache): self;

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): ?CompilerInterface;

    /**
     * @param CompilerInterface|null $compiler
     * @return $this
     * @throws ConfigException
     */
    public function setCompiler($compiler): self;

    /**
     * @param string $name
     * @return CachedEntryInterface
     * @throws ConfigException
     * @throws CompileException
     * @throws TemplateNotFoundException
     * @throws TemplateReadException
     */
    public function compileTemplate($name): CachedEntryInterface;

    /**
     * @param string $name
     * @param RuntimeHelperInterface|null $runtime
     */
    public function runTemplate($name, $runtime = null): void;
}
