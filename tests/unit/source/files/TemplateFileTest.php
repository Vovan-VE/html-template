<?php
namespace VovanVE\HtmlTemplate\tests\unit\source\files;

use VovanVE\HtmlTemplate\source\files\TemplateFile;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class TemplateFileTest extends BaseTestCase
{
    private const FILE_NAME = __FILE__ . '.tmp';
    private const FILE_CONTENT = "Test template content\n";

    private $modTime;

    public function testBasic()
    {
        $template = new TemplateFile('foo//bar', 'foo/bar', self::FILE_NAME);

        $this->assertEquals(self::FILE_CONTENT, $template->getContent(), 'content');

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(
            "mod-time: " . gmdate('Y-m-d H:i:s', $this->modTime) . " GMT\n",
            $template->getMeta(),
            'meta'
        );
    }

    protected function setUp()
    {
        if (false === file_put_contents(self::FILE_NAME, self::FILE_CONTENT)) {
            throw new \RuntimeException('Cannot write temp file');
        }

        // change time to let it differ to current time
        if (!touch(self::FILE_NAME, time() - (86400 + 3600 + 60 + 1))) {
            throw new \RuntimeException('Cannot change temp file mod time');
        }

        $this->modTime = filemtime(self::FILE_NAME);
    }

    protected function tearDown()
    {
        if (is_file(self::FILE_NAME) && !unlink(self::FILE_NAME)) {
            throw new \RuntimeException('Cannot delete temp file');
        }
    }
}
