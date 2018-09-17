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
     * @param string $path
     * @param string $classFormat
     * @param string $classNS
     * @throws ConfigException
     */
    public function __construct($classFormat, $classNS = '')
    {
        $this->classNamespace = $classNS;
        $this->classNameFormat = $classFormat;
        if (false === strpos($classFormat, self::PLACEHOLDER_HASH)) {
            throw new ConfigException('Class format does not contain placeholder: ' . self::PLACEHOLDER_HASH);
        }
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
     * @param string $key
     * @return string
     */
    protected function makeHash($key): string
    {
        return md5($key);
    }
}
