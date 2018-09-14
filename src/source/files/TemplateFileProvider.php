<?php
namespace VovanVE\HtmlTemplate\source\files;

use VovanVE\HtmlTemplate\source\TemplateInterface;
use VovanVE\HtmlTemplate\source\TemplateProvider;

class TemplateFileProvider extends TemplateProvider
{
    /** @var string */
    private $path;

    /**
     * @param string $name
     * @return string
     */
    protected static function makeKey($name): string
    {
        $result = strtr($name, '\\', '/');
        $result = trim($result, '/');
        return preg_replace('~/{2,}~', '/', $result);
    }

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct();

        $this->path = $path;
    }

    /**
     * @param string $name
     * @return TemplateInterface|null
     */
    protected function fetchTemplate($name): ?TemplateInterface
    {
        $key = static::makeKey($name);
        $filename = $this->makeFileName($key);
        if (!file_exists($filename)) {
            return null;
        }
        return new TemplateFile($name, $key, $filename);
    }

    /**
     * @param string $key
     * @return string
     */
    protected function makeFileName($key): string
    {
        return $this->path . \DIRECTORY_SEPARATOR . $this->makeFileBaseName($key);
    }

    /**
     * @param string $key
     * @return string
     */
    protected function makeFileBaseName($key): string
    {
        return $key;
    }
}
