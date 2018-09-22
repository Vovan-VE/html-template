<?php
namespace VovanVE\HtmlTemplate;

use VovanVE\HtmlTemplate\caching\CachedEntryInterface;
use VovanVE\HtmlTemplate\caching\CacheInterface;
use VovanVE\HtmlTemplate\caching\CacheWriteException;
use VovanVE\HtmlTemplate\compile\CompileException;
use VovanVE\HtmlTemplate\compile\CompilerInterface;
use VovanVE\HtmlTemplate\report\ReportInterface;
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
     */
    public function setTemplateProvider(?TemplateProviderInterface $provider): EngineInterface
    {
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
     */
    public function setCache(?CacheInterface $cache): EngineInterface
    {
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
     */
    public function setCompiler(?CompilerInterface $compiler): EngineInterface
    {
        $this->compiler = $compiler;
        return $this;
    }

    /**
     * @param string $name
     * @return CachedEntryInterface
     * @throws CacheWriteException
     * @throws CompileException
     * @throws ConfigException
     * @throws TemplateNotFoundException
     * @throws TemplateReadException
     */
    public function compileTemplate(string $name): CachedEntryInterface
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
     * @return ReportInterface
     * @throws ConfigException
     * @throws TemplateNotFoundException
     * @since 0.1.0
     */
    public function checkTemplateSyntax(string $name): ReportInterface
    {
        $templateProvider = $this->requireTemplateProvider();
        $compiler = $this->requireCompiler();

        $template = $templateProvider->getTemplate($name);

        return $compiler->syntaxCheck($template);
    }

    /**
     * @param string $name
     * @param RuntimeHelperInterface|null $runtime
     * @return string
     * @throws CacheWriteException
     * @throws CompileException
     * @throws ConfigException
     * @throws TemplateNotFoundException
     * @throws TemplateReadException
     */
    public function runTemplate(string $name, ?RuntimeHelperInterface $runtime = null): string
    {
        return $this
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
