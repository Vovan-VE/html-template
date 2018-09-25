<?php
namespace VovanVE\HtmlTemplate\tests\unit\base;

use VovanVE\HtmlTemplate\base\BaseObject;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class BaseObjectTest extends BaseTestCase
{
    public function testUndefinedGet()
    {
        $o = new BaseObject();
        $this->expectException(\OutOfRangeException::class);
        $this->fail("got value: " . $o->undefined);
    }

    public function testUndefinedSet()
    {
        $o = new BaseObject();
        $this->expectException(\OutOfRangeException::class);
        $o->undefined = 42;
    }

    public function testUndefinedIsset()
    {
        $o = new BaseObject();
        $this->expectException(\OutOfRangeException::class);
        $this->fail('got value: ' . (int)isset($o->undefined));
    }

    public function testUndefinedUnset()
    {
        $o = new BaseObject();
        $this->expectException(\OutOfRangeException::class);
        unset($o->undefined);
    }

    public function testDefined()
    {
        $o = new class(['lorem' => 37, 'ipsum' => 23]) extends BaseObject {
            public $lorem;
            public $ipsum = 42;
            public $dolor;
        };

        $this->assertEquals(37, $o->lorem);
        $this->assertEquals(23, $o->ipsum);
        $this->assertNull($o->dolor);

        $o->lorem = 19;
        $this->assertEquals(19, $o->lorem);

        $this->assertTrue(isset($o->lorem));
        $this->assertTrue(isset($o->ipsum));
        $this->assertFalse(isset($o->dolor));
    }
}
