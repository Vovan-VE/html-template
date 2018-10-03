<?php
namespace VovanVE\HtmlTemplate\components;

/**
 * @since 0.3.0
 */
class ComponentTraceException extends ComponentException
{
    /** @var string[] */
    protected $componentsStack;

    public function __construct(array $componentsStack = [], \Throwable $previous = null)
    {
        parent::__construct(
            'An error from component `' . join('` > `', $componentsStack) . '`',
            0,
            $previous
        );
        $this->componentsStack = $componentsStack;
    }

    /**
     * @param string $component
     * @return ComponentTraceException
     */
    public function nestInComponent(string $component): self
    {
        return new self(array_merge([$component], $this->componentsStack), $this->getPrevious());
    }

    /**
     * @return string[]
     */
    public function getComponentsStack(): array
    {
        return $this->componentsStack;
    }
}
