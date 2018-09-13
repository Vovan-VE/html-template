<?php
namespace VovanVE\HtmlTemplate\runtime;

interface RuntimeEntryDummyInterface
{
    /**
     * @param RuntimeHelperInterface $runtime
     */
    public static function run($runtime): void;
}
