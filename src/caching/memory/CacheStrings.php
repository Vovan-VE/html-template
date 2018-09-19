<?php
namespace VovanVE\HtmlTemplate\caching\memory;

use VovanVE\HtmlTemplate\caching\Cache;
use VovanVE\HtmlTemplate\caching\CachedEntryInterface;
use VovanVE\HtmlTemplate\caching\CacheInterface;

class CacheStrings extends Cache implements CacheInterface
{
    /** @var CacheStringEntry[] */
    private $entries = [];

    /**
     * @param string $key
     * @return CachedEntryInterface|null
     */
    public function getEntry(string $key): ?CachedEntryInterface
    {
        return $this->entries[$key] ?? null;
    }

    /**
     * @param string $key
     * @param string $content
     * @param string $meta
     * @return CachedEntryInterface
     */
    public function setEntry(string $key, string $content, string $meta): CachedEntryInterface
    {
        $has = $this->entries[$key] ?? null;
        if ($has && $has->getMeta() === $meta) {
            return $has;
        }

        $hash = $this->makeHash($key);
        return $this->entries[$key] = new CacheStringEntry(
            $this->makeClassName($hash),
            $content,
            $meta
        );
    }

    /**
     * @param string $key
     * @return void
     */
    public function deleteEntry(string $key): void
    {
        unset($this->entries[$key]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function entryExists(string $key): bool
    {
        return isset($this->entries[$key]);
    }
}
