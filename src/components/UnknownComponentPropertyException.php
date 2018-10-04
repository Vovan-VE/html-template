<?php
namespace VovanVE\HtmlTemplate\components;

use Throwable;

/**
 * @since 0.3.0
 */
class UnknownComponentPropertyException extends ComponentException
{
    /** @var string */
    private $property;

    public function __construct(string $property, Throwable $previous = null)
    {
        parent::__construct("Component does not support property `$property`", 0, $previous);
        $this->property = $property;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }
}
