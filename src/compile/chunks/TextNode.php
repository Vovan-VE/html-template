<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\runtime\RuntimeHelper;

class TextNode extends PhpStringConst
{
    public function __construct(string $htmlTextFlow)
    {
        parent::__construct($htmlTextFlow);
    }

    public static function createFromTextPlain(string $text): self
    {
        return new static(RuntimeHelper::htmlEncode($text));
    }
}
