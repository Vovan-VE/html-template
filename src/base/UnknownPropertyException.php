<?php
namespace VovanVE\HtmlTemplate\base;

use Throwable;
use VovanVE\HtmlTemplate\Exception;

/**
 * @since v0.3.0
 */
class UnknownPropertyException extends Exception
{
    /** @var string */
    private $property;

    public function __construct(string $property, Throwable $previous = null)
    {
        parent::__construct("Unknown property `$property`", 0, $previous);
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
