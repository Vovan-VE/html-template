<?php
namespace VovanVE\HtmlTemplate\tests\helpers\conversion;

use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class ExpectSuccess extends Expect
{
    /** @var string */
    private $expected;
    /** @var string */
    private $message;
    /** @var string */
    private $result;
    /** @var bool */
    private $resultIsFormat;

    /**
     * ExpectSuccess constructor.
     * @param string $message
     * @param string $source
     * @param bool $isFormat
     * @param string $expected
     * @param bool $resultIsFormat
     * @param string $result
     */
    public function __construct(
        string $message,
        string $source,
        bool $isFormat,
        string $expected,
        bool $resultIsFormat,
        string $result
    ) {
        parent::__construct($source, $isFormat);
        $this->expected = $expected;
        $this->message = $message;
        $this->resultIsFormat = $resultIsFormat;
        $this->result = $result;
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
     * @return bool
     */
    public function resultIsFormat(): bool
    {
        return $this->resultIsFormat;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
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
    }

    /**
     * @param BaseTestCase $test
     * @param string $filename
     * @param string $result
     * @return void
     */
    public function checkResult(BaseTestCase $test, string $filename, string $result): void
    {
        if ($this->resultIsFormat()) {
            $test::assertStringMatchesFormat($this->getResult(), $result, $this->getMessage());
        } else {
            $test::assertEquals($this->getResult(), $result, $this->getMessage());
        }
    }
}
