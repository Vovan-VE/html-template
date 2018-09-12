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
        $result = strtr($name, '\\', '/');
        $result = trim($result, '/');
        return preg_replace('~/+~', '~', $result);
    }

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct();

        $this->name = $name;
        $this->key = static::makeKey($name);
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
