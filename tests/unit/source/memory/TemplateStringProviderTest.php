<?php
namespace VovanVE\HtmlTemplate\tests\unit\source\memory;

use VovanVE\HtmlTemplate\source\memory\TemplateString;
use VovanVE\HtmlTemplate\source\memory\TemplateStringProvider;
use VovanVE\HtmlTemplate\source\TemplateNotFoundException;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class TemplateStringProviderTest extends BaseTestCase
{
    public function testBasic()
    {
        $foo_content = 'Lorem ipsum dolor';
        $bar_content = 'Sit amet consectepture';

        $provider = (new TemplateStringProvider)
            ->setTemplate('foo', $foo_content)
            ->setTemplate('bar', $bar_content);

        /** @noinspection PhpUnhandledExceptionInspection */
        $foo = $provider->getTemplate('foo');
        $this->assertInstanceOf(TemplateString::class, $foo);
        $this->assertEquals($foo_content, $foo->getContent());

        /** @noinspection PhpUnhandledExceptionInspection */
        $bar = $provider->getTemplate('bar');
        $this->assertInstanceOf(TemplateString::class, $bar);
        $this->assertEquals($bar_content, $bar->getContent());
    }

    public function testFlowInterface()
    {
        $provider = new TemplateStringProvider();

        $this->assertSame($provider, $provider->setTemplate('foo', ''));
        $this->assertSame($provider, $provider->deleteTemplate('foo'));
    }

    public function testDelete()
    {
        $provider = (new TemplateStringProvider)
            ->setTemplate('foo', '')
            ->deleteTemplate('foo');

        $this->expectException(TemplateNotFoundException::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $provider->getTemplate('foo');
    }
}
