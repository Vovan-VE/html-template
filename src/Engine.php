<?php
namespace VovanVE\HtmlTemplate;

use VovanVE\HtmlTemplate\caching\CachedEntryInterface;
use VovanVE\HtmlTemplate\caching\CacheInterface;
use VovanVE\HtmlTemplate\caching\CacheWriteException;
use VovanVE\HtmlTemplate\compile\CompileException;
use VovanVE\HtmlTemplate\compile\CompilerInterface;
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;
use VovanVE\HtmlTemplate\source\TemplateNotFoundException;
use VovanVE\HtmlTemplate\source\TemplateProviderInterface;
use VovanVE\HtmlTemplate\source\TemplateReadException;

class Engine implements EngineInterface
{
    /** @var TemplateProviderInterface|null */
    private $templateProvider;

    /** @var CacheInterface|null */
    private $cache;

    /** @var CompilerInterface */
    private $compiler;

    /**
     * @return TemplateProviderInterface|null
     */
    public function getTemplateProvider(): ?TemplateProviderInterface
    {
        return $this->templateProvider;
    }

    /**
     * @param TemplateProviderInterface|null $provider
     * @return $this
     * @throws ConfigException
     */
    public function setTemplateProvider($provider): EngineInterface
    {
        if (null !== $provider && !$provider instanceof TemplateProviderInterface) {
            throw new ConfigException('Template Provider must be ' . TemplateProviderInterface::class . ' or null');
        }
        $this->templateProvider = $provider;
        return $this;
    }

    /**
     * @return CacheInterface|null
     */
    public function getCache(): ?CacheInterface
    {
        return $this->cache;
    }

    /**
     * @param CacheInterface|null $cache
     * @return $this
     * @throws ConfigException
     */
    public function setCache($cache): EngineInterface
    {
        if (null !== $cache && !$cache instanceof CacheInterface) {
            throw new ConfigException('Cache must be ' . CacheInterface::class . ' or null');
        }
        $this->cache = $cache;
        return $this;
    }

    /**
     * @return CompilerInterface|null
     */
    public function getCompiler(): ?CompilerInterface
    {
        return $this->compiler;
    }

    /**
     * @param CompilerInterface|null $compiler
     * @return $this
     * @throws ConfigException
     */
    public function setCompiler($compiler): EngineInterface
    {
        if (null !== $compiler && !$compiler instanceof CompilerInterface) {
            throw new ConfigException('Cache must be ' . CompilerInterface::class . ' or null');
        }
        $this->compiler = $compiler;
        return $this;
    }

    /**
     * @param string $name
     * @return CachedEntryInterface
     * @throws ConfigException
     * @throws CacheWriteException
     * @throws CompileException
     * @throws TemplateNotFoundException
     * @throws TemplateReadException
     */
    public function compileTemplate($name): CachedEntryInterface
    {
        $templateProvider = $this->requireTemplateProvider();
        $cache = $this->requireCache();
        $compiler = $this->requireCompiler();

        $template = $templateProvider->getTemplate($name);
        $key = $template->getUniqueKey();
        $meta = $template->getMeta() . $this->getMeta() . $compiler->getMeta();

        $cached = $cache->getEntry($key);
        if (null === $cached || $cached->getMeta() !== $meta) {
            $compiled = $compiler->compile($template);
            $cached = $cache->setEntry($key, $compiled->getContent(), $meta);
        }
        return $cached;
    }

    /**
     * @param string $name
     * @param RuntimeHelperInterface|null $runtime
     * @throws ConfigException
     * @throws CacheWriteException
     * @throws CompileException
     * @throws TemplateNotFoundException
     * @throws TemplateReadException
     */
    public function runTemplate($name, $runtime = null): void
    {
        $this
            ->compileTemplate($name)
            ->run($runtime ?? new RuntimeHelper());
    }

    /**
     * @return TemplateProviderInterface
     * @throws ConfigException
     */
    private function requireTemplateProvider(): TemplateProviderInterface
    {
        $provider = $this->getTemplateProvider();
        if (null === $provider) {
            throw new ConfigException('Template Provider did not initialized');
        }
        return $provider;
    }

    /**
     * @return CacheInterface
     * @throws ConfigException
     */
    private function requireCache(): CacheInterface
    {
        $cache = $this->getCache();
        if (null === $cache) {
            throw new ConfigException('Cache did not initialized');
        }
        return $cache;
    }

    /**
     * @return CompilerInterface
     * @throws ConfigException
     */
    private function requireCompiler(): CompilerInterface
    {
        $compiler = $this->getCompiler();
        if (null === $compiler) {
            throw new ConfigException('Compiler did not initialized');
        }
        return $compiler;
    }

    /**
     * @return string
     */
    private function getMeta(): string
    {
        return "PHP: " . \PHP_VERSION . "\n";
    }
}
