<?php
namespace VovanVE\HtmlTemplate\tests\helpers\conversion;

use PHPUnit\Framework\Constraint\RegularExpression;
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
    public function __construct($source, $isFormat, $message)
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
     * @return void
     */
    public function setExpectations($test): void
    {
        if ($this->isFormat()) {
            $match = $test::matches($this->getMessage());

            // Why they did not add public interface
            // neither to get that stupid regexp,
            // nor to convert a format to a regexp?
            // What is the reason? Why it is a secret?
            // - or-
            // Why just not to add `expectExceptionMessageFormat()` ? Simply! Why?
            $regexp = (
                (function () {
                    /** @var RegularExpression $this */
                    /** @uses RegularExpression::$pattern */
                    return $this->{'pattern'};
                })
                    ->bindTo($match, RegularExpression::class)
            )();

            $test->expectExceptionMessageRegExp($regexp);
        } else {
            $test->expectExceptionMessage($this->getMessage());
        }
    }

    /**
     * @param BaseTestCase $test
     * @param string $result
     * @return void
     */
    public function checkResult($test, $result): void
    {
    }
}
