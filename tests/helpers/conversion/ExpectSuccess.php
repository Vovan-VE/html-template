<?php
namespace VovanVE\HtmlTemplate\tests\helpers\conversion;

use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class ExpectSuccess extends Expect
{
    /** @var string */
    private $expected;
    /** @var string */
    private $message;

    /**
     * ExpectSuccess constructor.
     * @param string $source
     * @param string $expected
     * @param bool $isFormat
     * @param string $message
     */
    public function __construct(string $source, string $expected, bool $isFormat, string $message)
    {
        parent::__construct($source, $isFormat);
        $this->expected = $expected;
        $this->message = $message;
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
     * @return void
     */
    public function setExpectations(BaseTestCase $test): void
    {
    }

    /**
     * @param BaseTestCase $test
     * @param string $result
     * @return void
     */
    public function checkResult(BaseTestCase $test, string $result): void
    {
        if ($this->isFormat()) {
            $test::assertStringMatchesFormat($this->getExpected(), $result, $this->getMessage());
        } else {
            $test::assertEquals($this->getExpected(), $result, $this->getMessage());
        }
    }
}
