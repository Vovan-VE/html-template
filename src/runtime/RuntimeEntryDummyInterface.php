<?php
namespace VovanVE\HtmlTemplate\runtime;

interface RuntimeEntryDummyInterface
{
    /**
     * @param RuntimeHelperInterface $runtime
     * @return string
     */
    public static function run(RuntimeHelperInterface $runtime): string;
}
