<?php
namespace VovanVE\HtmlTemplate\caching;

use VovanVE\HtmlTemplate\base\CodeFragment;
use VovanVE\HtmlTemplate\runtime\RuntimeEntryDummyInterface;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;

abstract class CacheEntry extends CodeFragment implements CachedEntryInterface
{
    /** @var string */
    private $className;

    /**
     * @param string $className
     */
    public function __construct($className)
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
     * @return void
     * @throws CacheConsistencyException
     */
    public function run($runtime): void
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
        $dummy::run($runtime);
    }

    /**
     * @return void
     */
    abstract protected function declareClass(): void;
}
