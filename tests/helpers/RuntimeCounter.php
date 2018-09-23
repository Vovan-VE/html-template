<?php
namespace VovanVE\HtmlTemplate\tests\helpers;

use VovanVE\HtmlTemplate\runtime\RuntimeHelper;

class RuntimeCounter extends RuntimeHelper
{
    /** @var int */
    private $runsCount = 0;

    public function didRun(): int
    {
        return ++$this->runsCount;
    }

    /**
     * @return int
     */
    public function getRunsCount(): int
    {
        return $this->runsCount;
    }

    /**
     * @param string $name
     * @param array $definitions
     * @return mixed
     */
    protected function getItemValue(string $name, array &$definitions)
    {
        $value = parent::getItemValue($name, $definitions);
        if (null === $value) {
            return $value = $definitions[$name] = "[value of &$name]";
        }
        return $value;
    }
}
