<?php
namespace VovanVE\HtmlTemplate\tests\unit\runtime;

use VovanVE\HtmlTemplate\components\BaseComponent;
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;
use VovanVE\HtmlTemplate\tests\helpers\TestComponent;

class RuntimeHelperTest extends BaseTestCase
{
    public function testParamPlain()
    {
        $values = [
            'int' => 42,
            'string' => "foo bar",
            'true' => true,
            'false' => false,
            'null' => null,
            'array' => [97, "lol", []],
        ];

        foreach (
            [
                new RuntimeHelper($values),
                (new RuntimeHelper)->setParams($values),
            ]
            as $runtime
        ) {
            /** @var RuntimeHelper $runtime */
            foreach ($values as $name => $value) {
                $this->assertSame($value, $runtime->param($name), "param($name)");
            }
        }
    }

    public function testParamClosure()
    {
        $foo_calls_count = 0;
        $null_calls_count = 0;

        $getters = [
            'foo' => function () use (&$foo_calls_count) {
                ++$foo_calls_count;
                return 42;
            },
            'null' => function () use (&$null_calls_count) {
                ++$null_calls_count;
                return null;
            },
        ];

        $runtime = (new RuntimeHelper)
            ->setParams($getters);

        $this->assertSame(42, $runtime->param('foo'), 'get(foo) 1');
        $this->assertSame(42, $runtime->param('foo'), 'get(foo) 2');
        $this->assertEquals(1, $foo_calls_count, 'foo calls count');

        $this->assertNull($runtime->param('null'), 'get(null) 1');
        $this->assertNull($runtime->param('null'), 'get(null) 2');
        $this->assertEquals(1, $null_calls_count, 'null calls count');
    }

    public function testUndefinedParam()
    {
        $runtime = new RuntimeHelper();

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

    public function testHtmlDecodeEntity()
    {
        foreach (
            [
                '' => '',
                'a' => 'a',
                'a&lt;b' => 'a<b',
                'a&gt;b' => 'a>b',
                'a&quot;&amp;&#039;b' => 'a"&\'b',
                'a&amp;amp;b' => 'a&amp;b',
                'a&nbsp;b' => "a\u{A0}b",
                'a&rarr;b' => 'a→b',
            ]
            as $source => $encoded
        ) {
            $this->assertEquals($encoded, RuntimeHelper::htmlDecodeEntity($source), "at `$source`");
        }
    }

    public function testCreateElementShort()
    {
        $this->assertEquals('<foo/>', RuntimeHelper::createElement('foo'));
        $this->assertEquals('<foo/>', RuntimeHelper::createElement('foo', []));
    }

    /**
     * @param string $element
     * @param array $attributes
     * @param array|null $content
     * @param string $expect
     * @dataProvider createElementDataProvider
     */
    public function testCreateElement($element, $attributes, $content, $expect)
    {
        $this->assertEquals($expect, RuntimeHelper::createElement($element, $attributes, $content));
    }

    public function createElementDataProvider()
    {
        return [
            ['div', [], null, '<div/>'],
            ['div', [], [], '<div></div>'],
            ['div', [], ['a&lt;b'], '<div>a&lt;b</div>'],
            ['div', [], ['a&lt;b', '<br/>'], '<div>a&lt;b<br/></div>'],
            ['DIV', [], null, '<DIV/>'],
            ['div', ['id' => 'foo'], null, '<div id="foo"/>'],
            ['div', ['id' => 'foo'], [], '<div id="foo"></div>'],
            ['div', ['foo' => 42], null, '<div foo="42"/>'],
            ['div', ['foo' => 42], [], '<div foo="42"></div>'],
            ['div', ['bar' => true], null, '<div bar/>'],
            ['div', ['bar' => true], [], '<div bar></div>'],
            ['div', ['lol' => false], null, '<div/>'],
            ['div', ['lol' => false], [], '<div></div>'],
            ['div', ['qux' => null], null, '<div/>'],
            ['div', ['qux' => null], [], '<div></div>'],
            ['div', ['id' => 'foo', 'foo' => 42], null, '<div id="foo" foo="42"/>'],
            ['div', ['id' => 'foo', 'foo' => 42], [], '<div id="foo" foo="42"></div>'],
            ['div', ['foo' => 42, 'id' => 'foo'], null, '<div foo="42" id="foo"/>'],
            ['div', ['foo' => 42, 'id' => 'foo'], [], '<div foo="42" id="foo"></div>'],
            ['div', ['id' => 'foo', 'foo' => 42, 'bar' => true, 'lol' => false, 'qux' => null], null,
                '<div id="foo" foo="42" bar/>'],
            ['div', ['id' => 'foo', 'foo' => 42, 'bar' => true, 'lol' => false, 'qux' => null], [],
                '<div id="foo" foo="42" bar></div>'],
            ['foo:bar', [], null, '<foo:bar/>'],
            ['foo:bar', [], [], '<foo:bar></foo:bar>'],
        ];
    }

    /**
     * @param string $name
     * @param array $props
     * @param array|null $content
     * @param string $expected
     * @dataProvider createComponentDataProvider
     */
    public function testCreateComponent(string $name, array $props, ?array $content, string $expected)
    {
        $runtime = (new RuntimeHelper)
            ->setComponents([
                'Test' => TestComponent::class,
                'Boo' => new class() extends BaseComponent {
                    public function render(?array $content = null): string
                    {
                        if (null === $content) {
                            return '<test:foo/>';
                        }
                        return '<test:foo>' . join('', $content) . '</test:foo>';
                    }
                },
            ]);

        $this->assertEquals($expected, $runtime->createComponent($name, $props, $content));
    }

    public function createComponentDataProvider()
    {
        return [
            ['Test', [], null,
                '<!-- Test Component: foo=null bar=null /-->'],
            ['Test', [], [],
                '<!-- Test Component: foo=null bar=null --><!-- /Test Component -->'],
            ['Test', [], ['text', '<br/>', '&lt;&gt;'],
                '<!-- Test Component: foo=null bar=null -->text<br/>&lt;&gt;<!-- /Test Component -->'],
            ['Test', ['foo' => 42], null,
                '<!-- Test Component: foo=42 bar=null /-->'],
            ['Test', ['foo' => '42'], null,
                '<!-- Test Component: foo="42" bar=null /-->'],
            ['Test', ['foo' => true, 'bar' => [10, '20', null]], null,
                '<!-- Test Component: foo=true bar=[10,"20",null] /-->'],

            ['Boo', [], null,
                '<test:foo/>'],
            ['Boo', [], [],
                '<test:foo></test:foo>'],
            ['Boo', [], ['text', '<br/>', '&lt;&gt;'],
                '<test:foo>text<br/>&lt;&gt;</test:foo>'],
        ];
    }
}
