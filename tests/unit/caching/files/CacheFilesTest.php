<?php
namespace VovanVE\HtmlTemplate\tests\unit\caching\files;

use VovanVE\HtmlTemplate\caching\files\CacheFileEntry;
use VovanVE\HtmlTemplate\caching\files\CacheFiles;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;
use VovanVE\HtmlTemplate\tests\helpers\RuntimeCounter;

class CacheFilesTest extends BaseTestCase
{
    private const PATH = __FILE__ . '.tmp';
    private const CLASS_NS = __CLASS__;
    private const CLASS_FORMAT = 'TempClass_%{hash}';

    private const KEYS = [
        'foo~bar',
        '!@#$%^&*',
    ];

    /**
     * @return CacheFiles
     * @throws \VovanVE\HtmlTemplate\ConfigException
     */
    public function testCreate(): CacheFiles
    {
        $this->expectNotToPerformAssertions();
        /** @noinspection PhpUnhandledExceptionInspection */
        return new CacheFiles(self::PATH, self::CLASS_FORMAT, self::CLASS_NS);
    }

    /**
     * @param CacheFiles $cache
     * @return CacheFiles
     * @depends testCreate
     */
    public function testGetOnEmpty($cache): CacheFiles
    {
        $copy = clone $cache;
        foreach (self::KEYS as $key) {
            $this->assertNull($copy->getEntry($key), 'initial');
        }
        return $cache;
    }

    /**
     * @param CacheFiles $cache
     * @return CacheFiles
     * @depends testGetOnEmpty
     * @throws \VovanVE\HtmlTemplate\caching\CacheWriteException
     */
    public function testSet($cache): CacheFiles
    {
        $copy = clone $cache;

        $this->expectNotToPerformAssertions();

        foreach (self::KEYS as $key) {
            $code =
                "<?php\n" .
                "        if (" . var_export($key, true) . " !== \$runtime->param('key')) {\n".
                "            throw new \\RuntimeException('Wrong code executed');\n".
                "        }\n".
                "        \$runtime->didRun();\n" .
                "?>";

            /** @noinspection PhpUnhandledExceptionInspection */
            $copy->setEntry($key, $code, "Meta of: $key\n");
        }
        return $copy;
    }

    /**
     * @param CacheFiles $orig
     * @param CacheFiles $copy
     * @depends testCreate
     * @depends testSet
     */
    public function testExistsWithData($orig, $copy)
    {
        foreach (['orig' => $orig, 'copy' => $copy] as $which => $cache) {
            /** @var CacheFiles $cache */
            foreach (self::KEYS as $key) {
                $this->assertTrue($cache->entryExists($key), "`$key` in $which");
            }

            $this->assertFalse($cache->entryExists('does not exist'), "unknown in $which");
        }
    }

    /**
     * @param CacheFiles $orig
     * @param CacheFiles $copy
     * @depends testCreate
     * @depends testSet
     * @depends testExistsWithData
     */
    public function testGetFulfilled($orig, $copy)
    {
        foreach (['orig' => $orig, 'copy' => $copy] as $which => $cache) {
            /** @var CacheFiles $cache */
            foreach (self::KEYS as $key) {
                $entry = $cache->getEntry($key);
                $this->assertInstanceOf(CacheFileEntry::class, $entry, "`$key` in $which");

                $runtime = new RuntimeCounter([
                    'key' => $key,
                ]);
                $entry->run($runtime);
                $entry->run($runtime);
                $this->assertEquals(2, $runtime->getRunsCount(), "`$key` in $which runs count");
            }
        }
    }

    /**
     * @param CacheFiles $cache
     * @return CacheFiles
     * @depends testSet
     * @depends testGetFulfilled
     * @throws \VovanVE\HtmlTemplate\caching\CacheWriteException
     */
    public function testDelete($cache): CacheFiles
    {
        $this->expectNotToPerformAssertions();
        foreach (self::KEYS as $key) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $cache->deleteEntry($key);
        }
        return $cache;
    }

    /**
     * @param CacheFiles $orig
     * @param CacheFiles $copy
     * @depends testCreate
     * @depends testDelete
     */
    public function testExistsAfterDelete($orig, $copy)
    {
        foreach (['orig' => $orig, 'copy' => $copy] as $which => $cache) {
            /** @var CacheFiles $cache */
            foreach (self::KEYS as $key) {
                $this->assertFalse($cache->entryExists($key), "`$key` in $which");
            }
        }
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
        foreach (new \DirectoryIterator(self::PATH) as $file) {
            if ($file->isFile() && preg_match('/\\.phpc(?:\\.META\\.txt)?$/D', $file->getBasename())) {
                if (!unlink($file->getPathname())) {
                    throw new \RuntimeException('Cannot delete file in temp directory');
                }
            }
        }
    }
}
