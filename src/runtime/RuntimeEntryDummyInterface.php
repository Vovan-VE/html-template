<?php
namespace VovanVE\HtmlTemplate\runtime;

use VovanVE\HtmlTemplate\components\ComponentException;

interface RuntimeEntryDummyInterface
{
    /**
     * @param RuntimeHelperInterface $runtime
     * @return string
     * @throws ComponentException
     */
    public static function run(RuntimeHelperInterface $runtime): string;
}
