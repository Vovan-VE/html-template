<?php
namespace VovanVE\HtmlTemplate\components;

use VovanVE\HtmlTemplate\base\UnknownPropertyException;

/**
 * @since 0.1.1
 */
interface ComponentSpawnerInterface
{
    /**
     * @param array $properties
     * @return ComponentInterface
     * @throws UnknownPropertyException
     */
    public function getComponent(array $properties = []): ComponentInterface;
}
