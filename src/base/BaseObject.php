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
     */
    public function __construct(array $props = [])
    {
        ObjectHelper::setObjectProperties($this, $props);
    }

    /**
     * @param string $name
     */
    public function __get(string $name)
    {
        throw new \OutOfRangeException("Getting unknown property `$name`");
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        throw new \OutOfRangeException("Setting unknown property `$name`");
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        throw new \OutOfRangeException("Checking unknown property `$name`");
    }

    /**
     * @param string $name
     * @return void
     */
    public function __unset(string $name): void
    {
        throw new \OutOfRangeException("Deleting unknown property `$name`");
    }
}
