<?php
namespace VovanVE\HtmlTemplate\tests\helpers\conversion;

use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class ExpectThrow extends Expect
{
    /** @var string */
    private $message;

    /**
     * @param string $source
     * @param bool $isFormat
     * @param string $message
     */
    public function __construct(string $source, bool $isFormat, string $message)
    {
        // cannot set expected exception with format instead of regexp
        // cannot build regexp from format

        parent::__construct($source, $isFormat);
        $this->message = $message;
    }

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
        $test::fail("Bad source was successfully compiled from `$filename`: $result");
    }

    /**
     * @param BaseTestCase $test
     * @param string $filename
     * @param string $result
     * @return void
     */
    public function checkResult(BaseTestCase $test, string $filename, string $result): void
    {
    }

    /**
     * @param BaseTestCase $test
     * @param \Exception $e
     * @param string $filename
     * @return bool
     */
    public function caught(BaseTestCase $test, \Exception $e, string $filename): bool
    {
        if ($this->isFormat()) {
            $test::assertStringMatchesFormat($this->getMessage(), $e->getMessage(), "file `$filename`");
        } else {
            $test::assertEquals($this->getMessage(), $e->getMessage(), "file `$filename`");
        }
        return true;
    }
}
