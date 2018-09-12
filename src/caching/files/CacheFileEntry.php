<?php
namespace VovanVE\HtmlTemplate\caching\files;

use VovanVE\HtmlTemplate\caching\CachedEntryInterface;
use VovanVE\HtmlTemplate\caching\CacheEntry;
use VovanVE\HtmlTemplate\caching\CacheReadException;

class CacheFileEntry extends CacheEntry implements CachedEntryInterface
{
    /** @var string */
    protected $filename;
    /** @var string */
    protected $metaFilename;

    private const META_FILENAME_TAG = '.META.txt';

    /** @var string|bool|null */
    private $meta;

    /**
     * @param string $filename
     * @return string
     */
    public static function makeMetaFilename($filename): string
    {
        return $filename . self::META_FILENAME_TAG;
    }

    /**
     * @param string $className
     * @param string $filename
     */
    public function __construct($className, $filename)
    {
        parent::__construct($className);

        $this->filename = $filename;
        $this->metaFilename = static::makeMetaFilename($filename);
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getMetaFilename(): string
    {
        return $this->metaFilename;
    }

    /**
     * @return string|null
     */
    public function getMeta(): ?string
    {
        if (null === $this->meta) {
            $this->meta = $this->fetchMeta() ?? false;
        }

        if (false === $this->meta) {
            return null;
        }

        return $this->meta;
    }

    /**
     * @return string
     * @throws CacheReadException
     */
    protected function fetchContent(): string
    {
        $content = file_get_contents($this->filename);
        if (false === $content) {
            throw new CacheReadException('Cannot read cached entry content');
        }
        return $content;
    }

    /**
     * @return string|null
     */
    protected function fetchMeta(): ?string
    {
        if (!file_exists($this->metaFilename)) {
            return null;
        }

        $meta = file_get_contents($this->metaFilename);
        if (false === $meta) {
            return null;
        }

        return $meta;
    }

    /**
     * @return void
     */
    protected function declareClass(): void
    {
        require $this->filename;
    }
}
