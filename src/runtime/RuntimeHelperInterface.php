<?php
namespace VovanVE\HtmlTemplate\runtime;

interface RuntimeHelperInterface
{
    /**
     * @param string $name
     * @return mixed
     */
    public function param($name);

    /**
     * @param string $name
     * @return void
     */
    public function renderBlock($name): void;

    /**
     * @param string $content
     * @param string $charset
     * @return string
     */
    public static function htmlEncode($content, $charset = 'UTF-8'): string;
}
