<?php
namespace VovanVE\HtmlTemplate\runtime;

class RuntimeHelper implements RuntimeHelperInterface
{
    /** @var array */
    private $params;

    /** @var array */
    private $blocks;

    /**
     * @param array $params
     * @param array $blocks
     */
    public function __construct($params = [], $blocks = [])
    {
        $this->params = $params;
        $this->blocks = $blocks;
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
     * @param array $blocks
     * @return $this
     */
    public function setBlocks(array $blocks): self
    {
        $this->blocks = $blocks;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function param($name)
    {
        return $this->getItemValue($name, $this->params);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function renderBlock($name): void
    {
        $this->renderItem($name, $this->blocks);
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

    /**
     * @param string $name
     * @param array $definitions
     * @return mixed
     */
    protected function getItemValue($name, &$definitions)
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

    /**
     * @param string $name
     * @param array $definitions
     * @return void
     */
    protected function renderItem($name, &$definitions): void
    {
        if (!isset($definitions[$name])) {
            return;
        }

        $value = $definitions[$name];

        if ($value instanceof \Closure) {
            $value();
            return;
        }

        echo $value;
    }
}
