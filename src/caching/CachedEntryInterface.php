<?php
namespace VovanVE\HtmlTemplate\caching;

use VovanVE\HtmlTemplate\base\CodeFragmentInterface;

interface CachedEntryInterface extends CodeFragmentInterface
{
    /**
     * @return string|null
     */
    public function getMeta(): ?string;

    /**
     * @param array $params
     * @return void
     */
    public function run($params = []): void;
}
