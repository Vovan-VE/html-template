<?php
namespace VovanVE\HtmlTemplate\base;

interface RuntimeEntryDummyInterface
{
    /**
     * @param array $params
     */
    public static function run($params = []): void;
}
