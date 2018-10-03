<?php
namespace VovanVE\HtmlTemplate\components;

/**
 * @since 0.3.0
 */
class UnknownComponentException extends ComponentException
{
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct('Unknown component', 0, $previous);
    }
}
