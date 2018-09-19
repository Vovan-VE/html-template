<?php
namespace VovanVE\HtmlTemplate\tests\unit\source\files;

use VovanVE\HtmlTemplate\source\files\TemplateFile;
use VovanVE\HtmlTemplate\source\files\TemplateFileProvider;
use VovanVE\HtmlTemplate\source\TemplateNotFoundException;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class TemplateFileProviderTest extends BaseTestCase
{
    private const PATH = __FILE__ . '.tmp';
    private const DIRS = [
        'lorem',
        'lorem/ipsum',
        'dolor',
    ];
    private const TEMPLATES = [
        'foo.tpl',
        'bar.tpl',
        'lorem/baz.tpl',
        'lorem/qux.tpl',
        'lorem/ipsum/lol.tpl',
        'dolor/lol.tpl',
    ];

    /**
     * @return TemplateFileProvider
     */
    public function testCreate(): TemplateFileProvider
    {
        $this->expectNotToPerformAssertions();
        return new TemplateFileProvider(self::PATH);
    }

    /**
     * @param TemplateFileProvider $provider
     * @depends testCreate
     */
    public function testSuccess(TemplateFileProvider $provider)
    {
        foreach (self::TEMPLATES as $name) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $template = $provider->getTemplate($name);

            $this->assertInstanceOf(TemplateFile::class, $template);
            $this->assertEquals("Template `$name`\n", $template->getContent());
        }
    }

    /**
     * @param TemplateFileProvider $provider
     * @depends testCreate
     */
    public function testNotFound(TemplateFileProvider $provider)
    {
        $this->expectException(TemplateNotFoundException::class);
        /** @noinspection PhpUnhandledExceptionInspection */
        $provider->getTemplate('does/not/exist.tpl');
    }

    public static function setUpBeforeClass()
    {
        if (is_dir(self::PATH)) {
            self::cleanTempDirectory();
        } else {
            if (!mkdir(self::PATH)) {
                throw new \RuntimeException('Cannot create temp directory');
            }
        }

        foreach (self::DIRS as $dir) {
            if (!mkdir(self::PATH . '/' . $dir)) {
                throw new \RuntimeException('Cannot create temp directory');
            }
        }

        foreach (self::TEMPLATES as $name) {
            $file = self::PATH . '/' . $name;
            if (false === file_put_contents($file, "Template `$name`\n")) {
                throw new \RuntimeException("Cannot create temp file");
            }
        }
    }

    public static function tearDownAfterClass()
    {
        if (is_dir(self::PATH)) {
            self::cleanTempDirectory();

            if (!rmdir(self::PATH)) {
                throw new \RuntimeException('Cannot delete temp directory');
            }
        }
    }

    protected static function cleanTempDirectory()
    {
        foreach (array_reverse(self::DIRS) as $dir) {
            $path = self::PATH . '/' . $dir;
            if (is_dir($path)) {
                self::cleanTempFiles($path);

                if (!rmdir($path)) {
                    throw new \RuntimeException('Cannot delete temp directory');
                }
            }
        }

        self::cleanTempFiles(self::PATH);
    }

    /**
     * @param string $path
     */
    protected static function cleanTempFiles(string $path): void
    {
        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isFile() && preg_match('/\\.tpl$/D', $file->getBasename())) {
                if (!unlink($file->getPathname())) {
                    throw new \RuntimeException('Cannot delete file in temp directory');
                }
            }
        }
    }
}
