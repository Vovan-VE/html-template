<?php
namespace VovanVE\HtmlTemplate\runtime;

class RuntimeHelper implements RuntimeHelperInterface
{
    /** @var array */
    private $params;
    /** @var array */
    private $values = [];

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function param($name)
    {
        if (isset($this->values[$name]) || array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }
        if (isset($this->params[$name]) || array_key_exists($name, $this->params)) {
            $value = $this->params[$name];
            if ($value instanceof \Closure) {
                $value = $value();
            }
            return $this->values[$name] = $value;
        }
        return null;
    }

    /**
     * @param string $content
     * @param string $charset
     * @return string
     */
    public static function htmlEncode($content, $charset = 'UTF-8'): string
    {
        return htmlspecialchars((string)$content, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
    }
}
