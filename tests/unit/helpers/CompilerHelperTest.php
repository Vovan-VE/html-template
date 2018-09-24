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

    /**
     * @param int $code
     * @param string $hex
     * @dataProvider utf8CharDataProvider
     */
    public function testUtf8CharFromCode($code, $hex)
    {
        $this->assertEquals($hex, bin2hex(CompilerHelper::utf8CharFromCode($code)), sprintf('U+%X', $code));
    }

    public function utf8CharDataProvider()
    {
        return [
            [0x0, '00'],
            [0x1, '01'],
            [0x2, '02'],
            [0x4, '04'],
            [0x8, '08'],
            [0x10, '10'],
            [0x20, '20'],
            [0x40, '40'],
            [0x7F, '7f'],
            [0x80, 'c280'],
            [0x81, 'c281'],
            [0x100, 'c480'],
            [0x200, 'c880'],
            [0x400, 'd080'],
            [0x7FF, 'dfbf'],
            [0x800, 'e0a080'],
            [0x801, 'e0a081'],
            [0x840, 'e0a180'],
            [0xFFFF, 'efbfbf'],
            [0x10000, 'f0908080'],
            [0x10001, 'f0908081'],
            [0x10040, 'f0908180'],
            [0x11000, 'f0918080'],
            [0x20000, 'f0a08080'],
            [0x40000, 'f1808080'],
            [0x80000, 'f2808080'],
            [0x100000, 'f4808080'],
            [0x10FFFF, 'f48fbfbf'],
        ];
    }

    /**
     * @param int $code
     * @dataProvider utf8CharFailDataProvider
     */
    public function testUtf8CharFromCodeFail($code)
    {
        $this->expectException(\OutOfRangeException::class);
        CompilerHelper::utf8CharFromCode($code);
    }

    public function utf8CharFailDataProvider()
    {
        return [
            [-42],
            [0x110000],
        ];
    }
}
