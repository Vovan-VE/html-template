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
     * @param string $content
     * @return string
     */
    public static function htmlEncode(string $content): string;

    /**
     * @param string $html
     * @return string
     * @since 0.1.0
     */
    public static function htmlDecodeEntity(string $html): string;

    /**
     * @param string $element
     * @param array $attributes
     * @param array|null $content
     * @return string
     * @since 0.1.0
     */
    public static function createElement(string $element, array $attributes = [], ?array $content = null): string;

    /**
     * @param string $code
     * @return string
     * @since 0.1.0
     */
    public static function createDocType(string $code): string;
}
