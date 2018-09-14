<?php
namespace VovanVE\HtmlTemplate\tests\unit\source;

use VovanVE\HtmlTemplate\source\Template;
use VovanVE\HtmlTemplate\source\TemplateInterface;
use VovanVE\HtmlTemplate\source\TemplateNotFoundException;
use VovanVE\HtmlTemplate\source\TemplateProvider;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class TemplateProviderTest extends BaseTestCase
{
    public function testCreate(): TemplateProvider
    {
        $this->expectNotToPerformAssertions();
        return new class extends TemplateProvider implements \Countable {
            private $fetches = [];

            public function getFetches(): array
            {
                return $this->fetches;
            }

            public function count()
            {
                return \count($this->templates);
            }

            /**
             * @param string $name
             * @return TemplateInterface
             */
            protected function fetchTemplate($name): TemplateInterface
            {
                if ('' === $name) {
                    throw new TemplateNotFoundException();
                }

                $this->fetches[$name] = ($this->fetches[$name] ?? 0) + 1;

                return new class($name, $name) extends Template {
                    protected function fetchContent(): string
                    {
                        return "Content of `{$this->getName()}`\n";
                    }

                    public function getMeta(): string
                    {
                        return "Meta of `{$this->getName()}`\n";
                    }
                };
            }
        };
    }

    /**
     * @param TemplateProvider|\Countable $provider
     * @return TemplateProvider
     * @depends testCreate
     * @throws TemplateNotFoundException
     * @throws \VovanVE\HtmlTemplate\source\TemplateReadException
     */
    public function testFetches($provider): TemplateProvider
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $first = $provider->getTemplate('foo/bar');

        $this->assertInstanceOf(Template::class, $first, 'first `foo/bar`');

        /** @noinspection PhpUnhandledExceptionInspection */
        $first_same = $provider->getTemplate('foo/bar');
        $this->assertSame($first, $first_same, 'second `foo/bar`');

        $this->assertEquals("Content of `foo/bar`\n", $first_same->getContent());
        $this->assertEquals("Meta of `foo/bar`\n", $first_same->getMeta());

        /** @noinspection PhpUnhandledExceptionInspection */
        $another = $provider->getTemplate('lorem-ipsum');
        $this->assertInstanceOf(Template::class, $another, '`lorem-ipsum`');

        $this->assertEquals("Content of `lorem-ipsum`\n", $another->getContent());
        $this->assertEquals("Meta of `lorem-ipsum`\n", $another->getMeta());

        $this->assertCount(2, $provider, 'fetched templates');

        return $provider;
    }

    /**
     * @param TemplateProvider|\Countable $provider
     * @depends testFetches
     */
    public function testClear($provider)
    {
        $provider->clear();
        $this->assertCount(0, $provider);
    }

    /**
     * @param TemplateProvider $provider
     * @depends testCreate
     */
    public function testFetcheFailure($provider)
    {
        $this->expectException(TemplateNotFoundException::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $provider->getTemplate('');
    }
}
