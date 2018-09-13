<?php
namespace VovanVE\HtmlTemplate\caching\files;

use VovanVE\HtmlTemplate\caching\CachedEntryInterface;
use VovanVE\HtmlTemplate\caching\CacheInterface;
use VovanVE\HtmlTemplate\caching\CacheWriteException;
use VovanVE\HtmlTemplate\ConfigException;
use VovanVE\HtmlTemplate\runtime\RuntimeEntryDummyInterface;

class CacheFiles implements CacheInterface
{
    /** @var string */
    private $path;
    /** @var string */
    private $classNamespace = 'RuntimeTemplate';
    /** @var string */
    private $classNameFormat = 'Compiled_' . self::PLACEHOLDER_HASH;

    private const RE_BASENAME = <<<'_REGEXP'
/
    (?(DEFINE)
        (?<word> [a-z0-9] [-_a-z0-9]+ )
        (?<name>
            (?&word)
            (?: \. (?&word) )*
        )
    )
    ^
    (?&name) (?: ~ (?&name) )*
    $
/xD
_REGEXP;

    private const FILE_EXT = '.phpc';
    private const PLACEHOLDER_HASH = '%{hash}';


    /** @var CacheFileEntry[]|bool[]  */
    private $loadedEntries = [];

    /**
     * @param string $path
     * @param string $classFormat
     * @param string $classNS
     * @throws ConfigException
     */
    public function __construct($path, $classFormat, $classNS = '')
    {
        $this->path = rtrim($path, \DIRECTORY_SEPARATOR) . \DIRECTORY_SEPARATOR;
        if (!is_dir($this->path)) {
            throw new ConfigException('Directory does not exist');
        }

        $this->classNamespace = $classNS;
        $this->classNameFormat = $classFormat;
        if (false === strpos($classFormat, self::PLACEHOLDER_HASH)) {
            throw new ConfigException('Class format does not contain placeholder: ' . self::PLACEHOLDER_HASH);
        }
    }

    /**
     * @param string $key
     * @return CachedEntryInterface|null
     */
    public function getEntry($key): ?CachedEntryInterface
    {
        return $this->loadedEntries[$key]
            ?? ($this->loadedEntries[$key] = $this->loadEntry($key) ?? false)
            ?: null;
    }

    /**
     * @param string $key
     * @param string $content
     * @param string $meta
     * @return CachedEntryInterface
     * @throws CacheWriteException
     */
    public function setEntry($key, $content, $meta): CachedEntryInterface
    {
        $has = $this->loadedEntries[$key] ?? null;
        if ($has && $has->getMeta() === $meta) {
            return $has;
        }

        $hash = $this->makeHash($key);
        $filename = $this->makeFilename($key, $hash);
        $metaFilename = CacheFileEntry::makeMetaFilename($filename);
        $className = $this->makeClassName($hash);

        $content = $this->makeClassContent($className, $content);

        // delete old meta file to break old state
        if (file_exists($metaFilename) && !unlink($metaFilename)) {
            throw new CacheWriteException("Cannot delete old meta file: $metaFilename");
        }

        // save main file
        if (false === file_put_contents($filename, $content, LOCK_EX)) {
            throw new CacheWriteException("Cannot write code file: $filename");
        }

        // save meta file
        if (false === file_put_contents($metaFilename, $meta, LOCK_EX)) {
            throw new CacheWriteException("Cannot write meta file: $metaFilename");
        }

        return $this->loadedEntries[$key] = new CacheFileEntry($className, $filename);
    }

    /**
     * @param string $key
     * @return void
     * @throws CacheWriteException
     */
    public function deleteEntry($key): void
    {
        $entry = $this->loadedEntries[$key] ?? null;
        if ($entry) {
            unset($this->loadedEntries[$key]);
            $file = $entry->getFilename();
            $metaFile = $entry->getMetaFilename();
        } else {
            $hash = $this->makeHash($key);
            $file = $this->makeFilename($key, $hash);
            $metaFile = CacheFileEntry::makeMetaFilename($file);
        }

        // delete old meta file to break old state
        if (file_exists($metaFile) && !unlink($metaFile)) {
            throw new CacheWriteException("Cannot delete meta file: $metaFile");
        }

        // delete old meta file to break old state
        if (file_exists($file) && !unlink($file)) {
            throw new CacheWriteException("Cannot delete code file: $file");
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function entryExists($key): bool
    {
        $entry = $this->loadedEntries[$key] ?? null;
        if ($entry) {
            $file = $entry->getFilename();
        } else {
            $hash = $this->makeHash($key);
            $file = $this->makeFilename($key, $hash);
        }

        return file_exists($file);
    }

    /**
     * @param string $key
     * @return CachedEntryInterface|null
     */
    protected function loadEntry($key): ?CachedEntryInterface
    {
        $hash = $this->makeHash($key);
        $filename = $this->makeFilename($key, $hash);
        if (!file_exists($filename)) {
            return null;
        }

        return new CacheFileEntry($this->makeClassName($hash), $filename);
    }

    /**
     * @param string $key
     * @return string
     */
    protected function makeHash($key): string
    {
        return md5($key);
    }

    /**
     * @param string $key
     * @param string $hash
     * @return string
     */
    protected function makeFilename($key, $hash): string
    {
        $basename = preg_match(self::RE_BASENAME, $key)
            ? $key
            : $hash;
        return $this->path . $basename . self::FILE_EXT;
    }

    /**
     * @param string $hash
     * @return string
     */
    protected function makeClassName($hash): string
    {
        $class = str_replace(self::PLACEHOLDER_HASH, $hash, $this->classNameFormat);
        if ($this->classNamespace) {
            return $this->classNamespace . '\\' . $class;
        }
        return $class;
    }

    /**
     * @param string $className
     * @param string $content
     * @param string $key
     * @return string
     */
    private function makeClassContent($className, $content)
    {
        $code = '<' . "?php\n"
            . "/**\n"
            . " * Generated code\n"
            . " * Time: " . gmdate('Y-m-d H:i:s') . " GMT\n"
            . " */\n\n";

        if ($this->classNamespace) {
            $code .= "namespace {$this->classNamespace};\n\n";
        }

        /** @uses RuntimeEntryDummyInterface::run() */
        $code .= "class $className {\n"
            . "    public static function run(\$runtime): void\n"
            . "    {\n"
            . "$content\n"
            . "    }\n"
            . "}\n";

        return $code;
    }
}
