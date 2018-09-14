<?php
namespace VovanVE\HtmlTemplate\tests\helpers;

use VovanVE\HtmlTemplate\runtime\RuntimeHelper;

class RuntimeCounter extends RuntimeHelper
{
    /** @var int */
    private $runsCount = 0;

    public function didRun(): void
    {
        $this->runsCount++;
    }

    /**
     * @return int
     */
    public function getRunsCount(): int
    {
        return $this->runsCount;
    }
}
