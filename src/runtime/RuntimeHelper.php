<?php
namespace VovanVE\HtmlTemplate\runtime;

use VovanVE\HtmlTemplate\base\UnknownPropertyException;
use VovanVE\HtmlTemplate\components\ComponentDefinitionException;
use VovanVE\HtmlTemplate\components\ComponentException;
use VovanVE\HtmlTemplate\components\ComponentInterface;
use VovanVE\HtmlTemplate\components\ComponentRuntimeException;
use VovanVE\HtmlTemplate\components\ComponentSpawnerInterface;
use VovanVE\HtmlTemplate\components\ComponentTraceException;
use VovanVE\HtmlTemplate\components\UnknownComponentException;
use VovanVE\HtmlTemplate\components\UnknownComponentPropertyException;
use VovanVE\HtmlTemplate\helpers\CompilerHelper;

class RuntimeHelper implements RuntimeHelperInterface
{
    /** @var array */
    private $params;
    /**
     * @var string[]|ComponentInterface[]|ComponentSpawnerInterface[]
     * @since 0.1.0
     */
    private $components = [];

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * @param array $params
     * @return self
     * @since 0.2.0
     */
    public function addParams(array $params): RuntimeHelperInterface
    {
        $new = clone $this;
        $new->params = $params + $new->params;
        return $new;
    }

    /**
     * @param array $params
     * @return $this
     * @deprecated >= 0.2.0: Use `addParams()` instead
     */
    public function setParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param array $components
     * @return self
     * @since 0.2.0
     */
    public function addComponents(array $components): RuntimeHelperInterface
    {
        $new = clone $this;
        $new->components = $components + $new->components;
        return $new;
    }

    /**
     * @param array $components
     * @return $this
     * @since 0.1.0
     * @deprecated >= 0.2.0: Use `addComponents()` instead
     */
    public function setComponents(array $components): self
    {
        $this->components = $components;
        return $this;
    }

    /**
     * @param string $name
     * @param string $class
     * @return $this
     * @since 0.1.0
     * @deprecated >= 0.2.0: Use `addComponents()` instead
     */
    public function setComponent(string $name, string $class): self
    {
        $this->components[$name] = $class;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function param(string $name)
    {
        return $this->getItemValue($name, $this->params);
    }

    /**
     * @param mixed $value
     * @return string
     * @since 0.4.0
     */
    public static function toString($value): string
    {
        if (is_string($value)) {
            return $value;
        }
        if (null === $value || is_bool($value)) {
            return '';
        }
        if (is_int($value) || is_float($value)) {
            return (string)$value;
        }
        if (is_array($value)) {
            return '[Array]';
        }
        throw new \InvalidArgumentException('Unsupported type');
    }

    /**
     * @param string $content
     * @return string
     */
    public static function htmlEncode($content): string
    {
        if (is_string($content)) {
            return CompilerHelper::htmlEncode($content);
        }
        /** @deprecated */
        if (null === $content || is_bool($content)) {
            return '';
        }
        if (is_int($content) || is_float($content)) {
            return (string)$content;
        }
        if (is_array($content)) {
            return '[Array]';
        }
        throw new \InvalidArgumentException('Unsupported type');
    }

    /**
     * @param string $html
     * @return string
     * @since 0.1.0
     * @deprecated >= 0.2.0: use `CompilerHelper::htmlDecodeEntity()`
     */
    public static function htmlDecodeEntity(string $html): string
    {
        return CompilerHelper::htmlDecodeEntity($html);
    }

    /**
     * @param string $element
     * @param array $attributes
     * @param string|null $content
     * @return string
     * @since 0.1.0
     */
    public static function createElement(string $element, array $attributes = [], ?string $content = null): string
    {
        $result = "<$element";
        foreach ($attributes as $name => $value) {
            if (null === $value || false === $value) {
                continue;
            }
            $result .= " $name";
            if (true !== $value) {
                $result .= '="' . static::htmlEncode($value) . '"';
            }
        }

        if (null === $content) {
            $result .= "/>";
        } else {
            $result .= ">";
            $result .= $content;
            $result .= "</$element>";
        }

        return $result;
    }

    /**
     * @param string $name
     * @param array $properties
     * @param \Closure|null $content
     * @return string
     * @throws ComponentTraceException
     * @since 0.1.0
     */
    public function createComponent(string $name, array $properties = [], ?\Closure $content = null): string
    {
        try {
            /** @var string $component_class_ */
            $component_definition = $this->components[$name] ?? null;
            if (null === $component_definition) {
                throw new UnknownComponentException();
            }

            if (is_string($component_definition)) {
                if (!class_exists($component_definition)) {
                    throw new ComponentDefinitionException(
                        "Component definition `$name` refers to unknown class"
                    );
                }
                if (!is_subclass_of($component_definition, ComponentInterface::class)) {
                    throw new ComponentDefinitionException(
                        "Component `$name` does not implement `ComponentInterface`"
                    );
                }

                try {
                    $component = new $component_definition($properties);
                } catch (UnknownPropertyException $e) {
                    throw new UnknownComponentPropertyException($e->getProperty());
                }
            } elseif (is_object($component_definition)) {
                if ($component_definition instanceof ComponentSpawnerInterface) {
                    try {
                        $component = $component_definition->getComponent($properties);
                    } catch (UnknownPropertyException $e) {
                        throw new UnknownComponentPropertyException($e->getProperty());
                    }
                } elseif ($component_definition instanceof ComponentInterface) {
                    $component = $component_definition;
                } else {
                    throw new ComponentDefinitionException(
                        "Component `$name` does not implement any of expected interfaces"
                    );
                }
            } else {
                throw new ComponentDefinitionException(
                    "Component definition `$name` has unsupported type"
                );
            }

            /** @var ComponentInterface $component */
            return $component->render($this, $content);
        } catch (ComponentTraceException $e) {
            throw $e->nestInComponent($name);
        } catch (ComponentException $e) {
            throw new ComponentTraceException([$name], $e);
        } catch (\Throwable $e) {
            throw new ComponentTraceException([$name], new ComponentRuntimeException('Component runtime error', 0, $e));
        }
    }

    /**
     * @param string $name
     * @param array $definitions
     * @return mixed
     */
    protected function getItemValue(string $name, array &$definitions)
    {
        if (!isset($definitions[$name]) && !array_key_exists($name, $definitions)) {
            return null;
        }

        $value = $definitions[$name];
        if ($value instanceof \Closure) {
            $value = $definitions[$name] = $value();
        }
        return $value;
    }
}
