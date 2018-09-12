<?php
namespace VovanVE\HtmlTemplate\caching;

interface CacheInterface
{
    /**
     * @param string $key
     * @return CachedEntryInterface|null
     */
    public function getEntry($key): ?CachedEntryInterface;

    /**
     * @param string $key
     * @param string $content
     * @param string $meta
     * @return CachedEntryInterface
     * @throws CacheWriteException
     */
    public function setEntry($key, $content, $meta): CachedEntryInterface;

    /**
     * @param string $key
     * @return void
     * @throws CacheWriteException
     */
    public function deleteEntry($key): void;

    /**
     * @param string $key
     * @return bool
     */
    public function entryExists($key): bool;
}
