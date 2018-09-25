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
    public function __construct(string $source, bool $isFormat)
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
     * @param string $filename
     * @param string $result
     * @return void
     */
    abstract public function checkCode(BaseTestCase $test, string $filename, string $result): void;

    /**
     * @param BaseTestCase $test
     * @param string $filename
     * @param string $result
     * @return void
     */
    abstract public function checkResult(BaseTestCase $test, string $filename, string $result): void;

    /**
     * @param BaseTestCase $test
     * @param \Exception $e
     * @return bool
     */
    public function caught(BaseTestCase $test, \Exception $e): bool
    {
        return false;
    }
}
