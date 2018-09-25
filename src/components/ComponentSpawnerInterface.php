<?php
namespace VovanVE\HtmlTemplate\components;

/**
 * @since 0.1.1
 */
interface ComponentSpawnerInterface
{
    public function getComponent(array $properties = []): ComponentInterface;
}
