<?php
namespace VovanVE\HtmlTemplate;

use VovanVE\HtmlTemplate\caching\CacheInterface;
use VovanVE\HtmlTemplate\compile\CompilerInterface;
use VovanVE\HtmlTemplate\source\TemplateProviderInterface;

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
     * @param array $params
     */
    public function runTemplate($name, $params = []): void;
}
