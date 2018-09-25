<?php
namespace VovanVE\HtmlTemplate\runtime;

use VovanVE\HtmlTemplate\components\ComponentInterface;
use VovanVE\HtmlTemplate\components\ComponentSpawnerInterface;
use VovanVE\HtmlTemplate\ConfigException;

class RuntimeHelper implements RuntimeHelperInterface
{
    /** @var array */
    private $params;
    /**
     * @var string[]|ComponentInterface[]|ComponentSpawnerInterface[]
     * @since 0.1.0
     */
    private $components = [];

    private const CHARSET = 'UTF-8';

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param array $components
     * @return $this
     * @since 0.1.0
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
     * @param string $content
     * @return string
     */
    public static function htmlEncode(string $content): string
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, self::CHARSET);
    }

    /**
     * @param string $html
     * @return string
     * @since 0.1.0
     */
    public static function htmlDecodeEntity(string $html): string
    {
        return html_entity_decode($html, ENT_QUOTES | ENT_HTML5, self::CHARSET);
    }

    /**
     * @param string $element
     * @param array $attributes
     * @param array|null $content
     * @return string
     * @since 0.1.0
     */
    public static function createElement(string $element, array $attributes = [], ?array $content = null): string
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
            foreach ($content as $item) {
                $result .= $item;
            }
            $result .= "</$element>";
        }

        return $result;
    }

    /**
     * @param string $name
     * @param array $properties
     * @param array|null $content
     * @return string
     * @throws ConfigException
     * @since 0.1.0
     */
    public function createComponent(string $name, array $properties = [], ?array $content = null): string
    {
        /** @var string $component_class_ */
        $component_definition = $this->components[$name] ?? null;
        if (null === $component_definition) {
            throw new ConfigException("Unknown component `$name`");
        }

        if (is_string($component_definition)) {
            if (!class_exists($component_definition)) {
                throw new ConfigException("Component definition `$name` refers to unknown class");
            }
            if (!is_subclass_of($component_definition, ComponentInterface::class)) {
                throw new ConfigException("Component `$name` does not implement `ComponentInterface`");
            }

            $component = new $component_definition($properties);
        } elseif (is_object($component_definition)) {
            if ($component_definition instanceof ComponentSpawnerInterface) {
                $component = $component_definition->getComponent($properties);
            } elseif ($component_definition instanceof ComponentInterface) {
                $component = $component_definition;
            } else {
                throw new ConfigException("Component `$name` does not implement any of expected interfaces");
            }
        } else {
            throw new ConfigException("Component definition `$name` has unsupported type");
        }

        /** @var ComponentInterface $component */
        return $component->render($content);
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
