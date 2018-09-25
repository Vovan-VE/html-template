<?php
namespace VovanVE\HtmlTemplate\source\files;

use VovanVE\HtmlTemplate\source\Template;
use VovanVE\HtmlTemplate\source\TemplateNotFoundException;
use VovanVE\HtmlTemplate\source\TemplateReadException;

class TemplateFile extends Template
{
    /** @var string */
    protected $filename;

    /**
     * @param string $name
     * @param string $key
     * @param string $filename
     */
    public function __construct(string $name, string $key, string $filename)
    {
        parent::__construct($name, $key);

        $this->filename = $filename;
    }

    /**
     * @return string
     * @throws TemplateNotFoundException
     * @throws TemplateReadException
     */
    protected function fetchContent(): string
    {
        if (!file_exists($this->filename)) {
            throw new TemplateNotFoundException('Template not found');
        }

        $content = file_get_contents($this->filename);
        if (false === $content) {
            throw new TemplateReadException('Cannot read file');
        }
        return $content;
    }

    /**
     * @return string
     * @throws TemplateNotFoundException
     * @throws TemplateReadException
     */
    public function getMeta(): string
    {
        if (!file_exists($this->filename)) {
            throw new TemplateNotFoundException('Template not found');
        }

        $mod_time = filemtime($this->filename);
        if (false === $mod_time) {
            throw new TemplateReadException('Cannot stat file');
        }

        return "mod-time: " . gmdate('Y-m-d H:i:s') . " GMT\n";
    }
}
