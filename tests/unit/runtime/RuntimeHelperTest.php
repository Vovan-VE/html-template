<?php
namespace VovanVE\HtmlTemplate\tests\unit\runtime;

use VovanVE\HtmlTemplate\components\BaseComponent;
use VovanVE\HtmlTemplate\components\ComponentInterface;
use VovanVE\HtmlTemplate\components\ComponentSpawnerInterface;
use VovanVE\HtmlTemplate\components\ComponentTraceException;
use VovanVE\HtmlTemplate\components\UnknownComponentException;
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;
use VovanVE\HtmlTemplate\tests\helpers\CounterStepComponent;
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

        $runtime = new RuntimeHelper($getters);

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

    public function testAddParams()
    {
        $a = new RuntimeHelper(['foo' => 42]);
        $b = $a->addParams(['bar' => 37]);
        $c = $b->addParams(['foo' => 23]);

        $this->assertNotSame($a, $b);
        $this->assertNotSame($b, $c);

        $this->assertEquals(42, $a->param('foo'));
        $this->assertNull($a->param('bar'));

        $this->assertEquals(42, $b->param('foo'));
        $this->assertEquals(37, $b->param('bar'));

        $this->assertEquals(23, $c->param('foo'));
        $this->assertEquals(37, $c->param('bar'));
    }

    public function testAddComponents()
    {
        $a = new RuntimeHelper();
        $b = $a->addComponents(['Test' => TestComponent::class]);
        $c = $b->addComponents(['Test' => CounterStepComponent::class]);

        $this->assertNotSame($a, $b);
        $this->assertNotSame($b, $c);

        $this->assertEquals((new TestComponent)->render($b), $b->createComponent('Test'));

        $this->assertEquals((new CounterStepComponent)->render($c), $c->createComponent('Test'));

        try {
            $a->createComponent('Test');
            $this->fail('did not throw exception ever');
        } catch (ComponentTraceException $e) {
            $this->assertEquals('An error from component `Test`', $e->getMessage());
            $this->assertEquals(['Test'], $e->getComponentsStack());

            $prev = $e->getPrevious();
            $this->assertInstanceOf(UnknownComponentException::class, $prev);
            return;
        }
    }

    /**
     * @param mixed $source
     * @param string $encoded
     * @dataProvider getHtmlEncodeDataProvider
     */
    public function testHtmlEncode($source, string $encoded)
    {
        $this->assertEquals($encoded, RuntimeHelper::htmlEncode($source));
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
            ->addComponents([
                'Test' => TestComponent::class,
                'Boo' => new class() extends BaseComponent {
                    public function render(
                        RuntimeHelperInterface $runtime,
                        ?\Closure $content = null
                    ): string {
                        if (null === $content) {
                            return '<test:foo/>';
                        }
                        return '<test:foo>' . join('', $content($runtime)) . '</test:foo>';
                    }
                },
                'Factory' => new class(97) implements ComponentSpawnerInterface {
                    /** @var TestComponent */
                    private $origin;

                    public function __construct($foo)
                    {
                        $this->origin = new TestComponent();
                        $this->origin->foo = $foo;
                    }

                    public function getComponent(array $properties = []): ComponentInterface
                    {
                        return (clone $this->origin)
                            ->setProperties($properties);
                    }
                },
            ]);

        $content_closure = null !== $content
            ? function () use ($content) {
                return $content;
            }
            : null;
        $this->assertEquals($expected, $runtime->createComponent($name, $props, $content_closure));
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

            ['Factory', [], null,
                '<!-- Test Component: foo=97 bar=null /-->'],
            ['Factory', [], [],
                '<!-- Test Component: foo=97 bar=null --><!-- /Test Component -->'],
            ['Factory', [], ['text', '<br/>', '&lt;&gt;'],
                '<!-- Test Component: foo=97 bar=null -->text<br/>&lt;&gt;<!-- /Test Component -->'],
            ['Factory', ['foo' => 42], null,
                '<!-- Test Component: foo=42 bar=null /-->'],
            ['Factory', ['foo' => '42'], null,
                '<!-- Test Component: foo="42" bar=null /-->'],
            ['Factory', ['foo' => true, 'bar' => [10, '20', null]], null,
                '<!-- Test Component: foo=true bar=[10,"20",null] /-->'],
        ];
    }

    public function getHtmlEncodeDataProvider()
    {
        return [
            ['', ''],
            ['a', 'a'],
            ['a<b', 'a&lt;b'],
            ['a>b', 'a&gt;b'],
            ['a"&\'b', 'a&quot;&amp;&#039;b'],
            ['a&amp;b', 'a&amp;amp;b'],
            [null, ''],
            [true, ''],
            [false, ''],
            [42, '42'],
            [42.37, '42.37'],
            [[], '[Array]'],
            [[42, 37, 'foo'], '[Array]'],
        ];
    }
}
