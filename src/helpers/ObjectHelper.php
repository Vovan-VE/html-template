<?php
namespace VovanVE\HtmlTemplate\helpers;

/**
 * Class ObjectHelper
 * @since 0.1.0
 */
class ObjectHelper
{
    /**
     * @param object $object
     * @param array $props
     */
    public static function setObjectProperties($object, array $props): void
    {
        foreach ($props as $name => $value) {
            $object->$name = $value;
        }
    }
}
