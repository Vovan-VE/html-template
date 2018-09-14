<?php
namespace VovanVE\HtmlTemplate\tests\unit\caching\files;

use VovanVE\HtmlTemplate\caching\files\CacheFileEntry;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;
use VovanVE\HtmlTemplate\tests\helpers\RuntimeCounter;

class CacheFileEntryTest extends BaseTestCase
{
    const CODE_FILE = __FILE__ . '.tmp';
    const META_FILE = self::CODE_FILE . '.META.txt';

    const NS = __CLASS__;
    const NAME = 'TempClass997';

    private $code;
    private $meta;

    public function testBasic()
    {
        $class = self::NS . '\\' . self::NAME;

        $entry = new CacheFileEntry($class, self::CODE_FILE);

        $this->assertEquals($class, $entry->getClassName(), 'class name');
        $this->assertEquals(self::CODE_FILE, $entry->getFilename(), 'code filename');
        $this->assertEquals(self::META_FILE, $entry->getMetaFilename(), 'meta filename');
        $this->assertEquals($this->code, $entry->getContent(), 'content fetching');
        $this->assertEquals($this->meta, $entry->getMeta(), 'meta fetching');

        $runtime = new RuntimeCounter();

        /** @noinspection PhpUnhandledExceptionInspection */
        $entry->run($runtime);
        /** @noinspection PhpUnhandledExceptionInspection */
        $entry->run($runtime);

        $this->assertEquals(2, $runtime->getRunsCount(), 'runs count');
    }

    protected function setUp()
    {
        $this->code =
            "<?php\n" .
            "namespace " . self::NS . ";\n" .
            "class " . self::NAME . " {\n" .
            "    public static function run(\$runtime): void {\n" .
            "        \$runtime->didRun();\n" .
            "    }\n" .
            "}";
        $this->meta = "Temp class build by " . __CLASS__ . "\n";

        if (false === file_put_contents(self::CODE_FILE, $this->code, LOCK_EX)) {
            throw new \RuntimeException('Cannot setup code file');
        }
        if (false === file_put_contents(self::META_FILE, $this->meta, LOCK_EX)) {
            throw new \RuntimeException('Cannot setup meta file');
        }
    }

    protected function tearDown()
    {
        if (is_file(self::CODE_FILE) && !unlink(self::CODE_FILE)) {
            throw new \RuntimeException('Cannot delete code file');
        }
        if (is_file(self::META_FILE) && !unlink(self::META_FILE)) {
            throw new \RuntimeException('Cannot delete code file');
        }
    }
}
