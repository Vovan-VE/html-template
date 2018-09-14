<?php
namespace VovanVE\HtmlTemplate\tests\unit\helpers;

use VovanVE\HtmlTemplate\helpers\CompilerHelper;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class CompilerHelperTest extends BaseTestCase
{
    public function testCalcLineNumber()
    {
        foreach (
            [
                [1, 0, ""],

                [1, 0, "foo"],
                [1, 2, "foo"],
                [1, 3, "foo"],

                [1, 3, "foo\n"],
                [1, 3, "foo\r"],
                [1, 3, "foo\r\n"],

                [2, 4, "foo\n"],
                [2, 4, "foo\r"],
                [2, 5, "foo\r\n"],

                [2, 4, "foo\nbar"],
                [2, 4, "foo\rbar"],
                [2, 5, "foo\r\nbar"],

                [2, 5, "foo\nbar"],
                [2, 5, "foo\rbar"],
                [2, 6, "foo\r\nbar"],

                [2, 7, "foo\nbar"],
                [2, 7, "foo\rbar"],
                [2, 8, "foo\r\nbar"],

                [10, 29, "foo\nbar\n\nlol\rbaz\r\rqwe\r\nqux\r\n\r\nsux"],
            ]
            as $i => [$line, $offset, $text]
        ) {
            $this->assertEquals($line, CompilerHelper::calcLineNumber($text, $offset), "ASCII #$i");
        }

        foreach (
            [
                [1, "абв"],

                [2, "абв\n"],
                [2, "абв\r"],
                [2, "абв\r\n"],

                [2, "абв\nгде"],
                [2, "абв\rгде"],
                [2, "абв\r\nгде"],

                [3, "абв\nгде\n"],
                [3, "абв\rгде\r"],
                [3, "абв\r\nгде\r\n"],

                [10, "абв\nгде\n\nёжз\rийк\r\rлмн\r\nорп\r\n\r\nсту"],
            ]
            as $i => [$line, $text]
        ) {
            $offset = strlen($text);
            $this->assertEquals($line, CompilerHelper::calcLineNumber($text, $offset), "UTF-8 #$i full");
            $this->assertEquals($line, CompilerHelper::calcLineNumber("$text\n", $offset), "UTF-8 #$i append EOL");
            $this->assertEquals($line, CompilerHelper::calcLineNumber("{$text}эюя", $offset), "UTF-8 #$i append text");
        }
    }
}
