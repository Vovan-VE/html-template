<?php
namespace VovanVE\HtmlTemplate\runtime;

interface RuntimeHelperInterface
{
    /**
     * @param string $name
     * @return mixed
     */
    public function param(string $name);

    /**
     * @param string $name
     * @return void
     */
    public function renderBlock(string $name): void;

    /**
     * @param string $content
     * @param string $charset
     * @return string
     */
    public static function htmlEncode($content, string $charset = 'UTF-8'): string;
}
