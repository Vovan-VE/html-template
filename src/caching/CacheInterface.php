<?php
namespace VovanVE\HtmlTemplate\caching;

interface CacheInterface
{
    /**
     * @param string $key
     * @return CachedEntryInterface|null
     */
    public function getEntry(string $key): ?CachedEntryInterface;

    /**
     * @param string $key
     * @param string $content
     * @param string $meta
     * @return CachedEntryInterface
     * @throws CacheWriteException
     */
    public function setEntry(string $key, string $content, string $meta): CachedEntryInterface;

    /**
     * @param string $key
     * @return void
     * @throws CacheWriteException
     */
    public function deleteEntry(string $key): void;

    /**
     * @param string $key
     * @return bool
     */
    public function entryExists(string $key): bool;
}
