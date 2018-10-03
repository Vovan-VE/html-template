<?php
namespace VovanVE\HtmlTemplate\base;

use VovanVE\HtmlTemplate\helpers\ObjectHelper;

/**
 * Class BaseObject
 * @since 0.1.0
 */
class BaseObject
{
    /**
     * @param array $props
     * @throws UnknownPropertyException
     */
    public function __construct(array $props = [])
    {
        $this->setProperties($props);
    }

    /**
     * @param array $props
     * @return $this
     * @since v0.3.0
     * @throws UnknownPropertyException
     */
    public function setProperties(array $props): self
    {
        ObjectHelper::setObjectProperties($this, $props);
        return $this;
    }

    /**
     * @param string $name
     */
    public function __get(string $name)
    {
        throw new UnknownPropertyException($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        throw new UnknownPropertyException($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        throw new UnknownPropertyException($name);
    }

    /**
     * @param string $name
     * @return void
     */
    public function __unset(string $name): void
    {
        throw new UnknownPropertyException($name);
    }
}
