<?php
namespace VovanVE\HtmlTemplate\base;

abstract class CodeFragment implements CodeFragmentInterface
{
    /** @var string|null */
    protected $content = null;

    /**
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content ?? ($this->content = $this->fetchContent());
    }

    /**
     * @return string
     */
    abstract protected function fetchContent(): string;
}
