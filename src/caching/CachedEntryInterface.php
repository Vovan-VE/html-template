<?php
namespace VovanVE\HtmlTemplate\caching;

use VovanVE\HtmlTemplate\base\CodeFragmentInterface;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;
use VovanVE\HtmlTemplate\runtime\RuntimeTemplateException;

interface CachedEntryInterface extends CodeFragmentInterface
{
    /**
     * @return string|null
     */
    public function getMeta(): ?string;

    /**
     * @param RuntimeHelperInterface $runtime
     * @return string
     * @throws RuntimeTemplateException
     */
    public function run(RuntimeHelperInterface $runtime): string;
}
