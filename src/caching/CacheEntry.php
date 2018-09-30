<?php
namespace VovanVE\HtmlTemplate\caching;

use VovanVE\HtmlTemplate\base\CodeFragment;
use VovanVE\HtmlTemplate\runtime\RuntimeEntryDummyInterface;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;
use VovanVE\HtmlTemplate\runtime\RuntimeTemplateException;

abstract class CacheEntry extends CodeFragment implements CachedEntryInterface
{
    /** @var string */
    private $className;

    /**
     * @param string $className
     */
    public function __construct(string $className)
    {
        parent::__construct();

        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param RuntimeHelperInterface $runtime
     * @return string
     * @throws CacheConsistencyException
     * @throws RuntimeTemplateException
     */
    public function run(RuntimeHelperInterface $runtime): string
    {
        $class = $this->getClassName();
        if (!class_exists($class, false)) {
            $this->declareClass();
            if (!class_exists($class, false)) {
                throw new CacheConsistencyException("Runtime class `$class` still does not exist");
            }
            if (!method_exists($class, 'run')) {
                throw new CacheConsistencyException("Runtime class `$class` violates interface");
            }
        }

        /** @var RuntimeEntryDummyInterface|string $dummy */
        $dummy = $class;
        try {
            return $dummy::run($runtime);
        } catch (\Throwable $e) {
            throw new RuntimeTemplateException('An error occurred while executing template', 0, $e);
        }
    }

    /**
     * @return void
     */
    abstract protected function declareClass(): void;
}
