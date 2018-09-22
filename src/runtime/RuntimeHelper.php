<?php
namespace VovanVE\HtmlTemplate\runtime;

class RuntimeHelper implements RuntimeHelperInterface
{
    /** @var array */
    private $params;

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
        return htmlspecialchars((string)$content, ENT_QUOTES | ENT_SUBSTITUTE, self::CHARSET);
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
     * @param array $content
     * @return string
     * @since 0.1.0
     */
    public static function createElement(string $element, array $attributes, ?array $content = null): string
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
     * @param string $code
     * @return string
     * @since 0.1.0
     */
    public static function createDocType(string $code): string
    {
        return "<!DOCTYPE $code>";
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
