<?php
namespace VovanVE\HtmlTemplate\caching;

use VovanVE\HtmlTemplate\ConfigException;

abstract class Cache implements CacheInterface
{
    private const PLACEHOLDER_HASH = '%{hash}';

    /** @var string */
    private $classNamespace = 'RuntimeTemplate';
    /** @var string */
    private $classNameFormat = 'Compiled_' . self::PLACEHOLDER_HASH;

    /**
     * @param string $classFormat
     * @param string $classNS
     * @throws ConfigException
     */
    public function __construct(string $classFormat, string $classNS = '')
    {
        if (false === strpos($classFormat, self::PLACEHOLDER_HASH)) {
            throw new ConfigException('Class format does not contain placeholder: ' . self::PLACEHOLDER_HASH);
        }

        $this->classNamespace = $classNS;
        $this->classNameFormat = $classFormat;
    }

    /**
     * @param string $hash
     * @return string
     */
    protected function makeClassName(string $hash): string
    {
        $class = str_replace(self::PLACEHOLDER_HASH, $hash, $this->classNameFormat);
        if ($this->classNamespace) {
            return $this->classNamespace . '\\' . $class;
        }
        return $class;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function makeHash(string $key): string
    {
        return md5($key);
    }
}
