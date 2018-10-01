<?php
namespace VovanVE\HtmlTemplate\compile;

class CompileScope
{
    private $nextVar = 'a';

    public function newTempVar(): string
    {
        $name = $this->nextVar;
        $this->nextVar++;
        return '$_t' . $name;
    }
}
