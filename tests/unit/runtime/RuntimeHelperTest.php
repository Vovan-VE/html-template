<?php
namespace VovanVE\HtmlTemplate\tests\unit\runtime;

use VovanVE\HtmlTemplate\runtime\RuntimeHelper;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class RuntimeHelperTest extends BaseTestCase
{
    public function testParamPlain()
    {
        $params = [
            'int' => 42,
            'string' => "foo bar",
            'true' => true,
            'false' => false,
            'null' => null,
            'array' => [97, "lol", []],
        ];
        $runtime = new RuntimeHelper($params);

        foreach ($params as $name => $value) {
            $this->assertSame($value, $runtime->param($name), "param($name)");
        }
    }

    public function testParamClosure()
    {
        $foo_calls_count = 0;
        $null_calls_count = 0;

        $runtime = new RuntimeHelper([
            'foo' => function () use (&$foo_calls_count) {
                ++$foo_calls_count;
                return 42;
            },
            'null' => function () use (&$null_calls_count) {
                ++$null_calls_count;
                return null;
            },
        ]);

        $this->assertSame(42, $runtime->param('foo'), 'get(foo) 1');
        $this->assertSame(42, $runtime->param('foo'), 'get(foo) 2');
        $this->assertEquals(1, $foo_calls_count, 'foo calls count');

        $this->assertNull($runtime->param('null'), 'get(null) 1');
        $this->assertNull($runtime->param('null'), 'get(null) 2');
        $this->assertEquals(1, $null_calls_count, 'null calls count');
    }

    public function testUndefined()
    {
        $runtime = new RuntimeHelper([]);

        $this->assertNull($runtime->param('foo'));
        $this->assertNull($runtime->param('bar'));
    }

    public function testHtmlEncode()
    {
        foreach (
            [
                '' => '',
                'a' => 'a',
                'a<b' => 'a&lt;b',
                'a>b' => 'a&gt;b',
                'a"&\'b' => 'a&quot;&amp;&#039;b',
                'a&amp;b' => 'a&amp;amp;b',
            ]
            as $source => $encoded
        ) {
            $this->assertEquals($encoded, RuntimeHelper::htmlEncode($source), "at `$source`");
        }
    }
}
