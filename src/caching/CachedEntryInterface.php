<?php
namespace VovanVE\HtmlTemplate\caching;

use VovanVE\HtmlTemplate\base\CodeFragmentInterface;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;

interface CachedEntryInterface extends CodeFragmentInterface
{
    /**
     * @return string|null
     */
    public function getMeta(): ?string;

    /**
     * @param RuntimeHelperInterface $runtime
     * @return void
     */
    public function run($runtime): void;
}
