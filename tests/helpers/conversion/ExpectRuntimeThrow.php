<?php
namespace VovanVE\HtmlTemplate\tests\helpers\conversion;

use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class ExpectRuntimeThrow extends Expect
{
    /** @var string */
    private $message;
    /** @var string */
    private $expected;
    /** @var bool */
    private $throwIsFormat;
    /** @var string */
    private $throw;

    private $didCodeMatch = false;

    /**
     * ExpectSuccess constructor.
     * @param string $message
     * @param string $source
     * @param bool $isFormat
     * @param string $expected
     * @param bool $throwIsFormat
     * @param string $throw
     */
    public function __construct(
        string $message,
        string $source,
        bool $isFormat,
        string $expected,
        bool $throwIsFormat,
        string $throw
    ) {
        parent::__construct($source, $isFormat);
        $this->message = $message;
        $this->expected = $expected;
        $this->throwIsFormat = $throwIsFormat;
        $this->throw = $throw;
    }

    /**
     * @return string
     */
    public function getExpected(): string
    {
        return $this->expected;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param BaseTestCase $test
     * @param string $filename
     * @param string $result
     * @return void
     */
    public function checkCode(BaseTestCase $test, string $filename, string $result): void
    {
        if ($this->isFormat()) {
            $test::assertStringMatchesFormat($this->getExpected(), $result, $this->getMessage());
        } else {
            $test::assertEquals($this->getExpected(), $result, $this->getMessage());
        }

        $this->didCodeMatch = true;
    }

    /**
     * @param BaseTestCase $test
     * @param string $filename
     * @param string $result
     * @return void
     */
    public function checkResult(BaseTestCase $test, string $filename, string $result): void
    {
        $test::fail("Code was successfully executed `$filename`");
    }

    /**
     * @param BaseTestCase $test
     * @param \Exception $e
     * @param string $filename
     * @return bool
     */
    public function caught(BaseTestCase $test, \Exception $e, string $filename): bool
    {
        if (!$this->didCodeMatch) {
            return false;
        }

        $parts = explode("\n-- prev --\n", $this->throw);
        $level = 1;
        while ($e && $parts) {
            $part = array_shift($parts);
            if ($this->isFormat()) {
                $test::assertStringMatchesFormat($part, $e->getMessage(), "level $level; file $filename");
            } else {
                $test::assertEquals($part, $e->getMessage(), "level $level; file $filename");
            }
            $e = $e->getPrevious();
            $level++;
        }
        if ($parts) {
            $test::fail("Exception does not have more nested exceptions for level $level; file $filename");
        }

        return true;
    }
}
