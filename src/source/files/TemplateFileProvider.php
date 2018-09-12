<?php
namespace VovanVE\HtmlTemplate\source\files;

use VovanVE\HtmlTemplate\source\TemplateInterface;
use VovanVE\HtmlTemplate\source\TemplateProvider;

class TemplateFileProvider extends TemplateProvider
{
    /** @var string */
    private $path;

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
     * @return TemplateInterface
     */
    protected function fetchTemplate($name): TemplateInterface
    {
        return new TemplateFile($name, $this->makeFileName($name));
    }

    /**
     * @param string $name
     * @return string
     */
    protected function makeFileName($name): string
    {
        return $this->path . \DIRECTORY_SEPARATOR . $name;
    }
}
