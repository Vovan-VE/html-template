<?php
namespace VovanVE\HtmlTemplate\compile;

use VovanVE\HtmlTemplate\base\CodeFragment;

class CompiledEntry extends CodeFragment implements CompiledEntryInterface
{
    /**
     * @param string $content
     */
    public function __construct(string $content)
    {
        parent::__construct();

        $this->content = $content;
    }

    /**
     * @return string
     */
    protected function fetchContent(): string
    {
        throw new \LogicException('Not implemented as useless');
    }
}
