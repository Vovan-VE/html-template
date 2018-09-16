<?php
namespace VovanVE\HtmlTemplate\tests\helpers\conversion;

use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

abstract class Expect
{
    /** @var string */
    private $source;
    /** @var bool */
    private $isFormat = false;

    /**
     * @param string $source
     * @param bool $isFormat
     */
    public function __construct($source, $isFormat)
    {
        $this->source = $source;
        $this->isFormat = $isFormat;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    public function isFormat(): bool
    {
        return $this->isFormat;
    }

    /**
     * @param BaseTestCase $test
     * @return void
     */
    abstract public function setExpectations($test): void;

    /**
     * @param BaseTestCase $test
     * @param string $result
     * @return void
     */
    abstract public function checkResult($test, $result): void;
}
