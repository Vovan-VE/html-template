<?php
namespace VovanVE\HtmlTemplate\source;

use VovanVE\HtmlTemplate\base\CodeFragment;

abstract class Template extends CodeFragment implements TemplateInterface
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $key;

    /**
     * @param string $name
     * @return string
     */
    protected static function makeKey($name): string
    {
        return $name;
    }

    /**
     * @param string $name
     * @param $key
     */
    public function __construct($name, $key = null)
    {
        parent::__construct();

        $this->name = $name;
        $this->key = $key ?? $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUniqueKey(): string
    {
        return $this->key;
    }
}
