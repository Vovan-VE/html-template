<?php
namespace VovanVE\HtmlTemplate\tests\unit\caching\memory;

use VovanVE\HtmlTemplate\caching\memory\CacheStringEntry;
use VovanVE\HtmlTemplate\caching\memory\CacheStrings;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;
use VovanVE\HtmlTemplate\tests\helpers\RuntimeCounter;

class CacheStringsTest extends BaseTestCase
{
    private const CLASS_NS = __CLASS__;
    private const CLASS_FORMAT = 'TempClass_%{hash}';

    private const KEYS = [
        'foo~bar',
        '!@#$%^&*',
    ];


    /**
     * @return CacheStrings
     */
    public function testCreate(): CacheStrings
    {
        $this->expectNotToPerformAssertions();
        /** @noinspection PhpUnhandledExceptionInspection */
        return new CacheStrings(self::CLASS_FORMAT, self::CLASS_NS);
    }

    /**
     * @param CacheStrings $cache
     * @return CacheStrings
     * @depends testCreate
     */
    public function testGetOnEmpty(CacheStrings $cache): CacheStrings
    {
        $copy = clone $cache;
        foreach (self::KEYS as $key) {
            $this->assertNull($copy->getEntry($key), 'initial');
        }
        return $cache;
    }

    /**
     * @param CacheStrings $cache
     * @return CacheStrings
     * @depends testGetOnEmpty
     */
    public function testSet(CacheStrings $cache): CacheStrings
    {
        $copy = clone $cache;

        $this->expectNotToPerformAssertions();

        foreach (self::KEYS as $key) {
            /** @uses RuntimeCounter::didRun() */
            $code =
                "(function () use (\$runtime) {\n" .
                "    if (" . var_export($key, true) . " !== \$runtime->param('key')) {\n" .
                "        throw new \\RuntimeException('Wrong code executed');\n" .
                "    }\n" .
                "    return (string)\$runtime->didRun();\n" .
                "})()";

            /** @noinspection PhpUnhandledExceptionInspection */
            $copy->setEntry($key, $code, "Meta of: $key\n");
        }
        return $copy;
    }

    /**
     * @param CacheStrings $cache
     * @return CacheStrings
     * @depends testSet
     */
    public function testExistsWithData(CacheStrings $cache): CacheStrings
    {
        /** @var CacheStrings $cache */
        foreach (self::KEYS as $key) {
            $this->assertTrue($cache->entryExists($key), "`$key`");
        }

        $this->assertFalse($cache->entryExists('does not exist'), "unknown");
        return $cache;
    }

    /**
     * @param CacheStrings $cache
     * @return CacheStrings
     * @depends testExistsWithData
     */
    public function testGetFulfilled(CacheStrings $cache): CacheStrings
    {
        foreach (self::KEYS as $key) {
            $entry = $cache->getEntry($key);
            $this->assertInstanceOf(CacheStringEntry::class, $entry, "`$key`");

            $runtime = new RuntimeCounter([
                'key' => $key,
            ]);
            $this->assertEquals('1', $entry->run($runtime));
            $this->assertEquals('2', $entry->run($runtime));
            $this->assertEquals(2, $runtime->getRunsCount(), "`$key` runs count");
        }
        return $cache;
    }

    /**
     * @param CacheStrings $cache
     * @return CacheStrings
     * @depends testGetFulfilled
     */
    public function testDelete(CacheStrings $cache): CacheStrings
    {
        $this->expectNotToPerformAssertions();
        foreach (self::KEYS as $key) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $cache->deleteEntry($key);
        }
        return $cache;
    }

    /**
     * @param CacheStrings $cache
     * @depends testDelete
     */
    public function testExistsAfterDelete(CacheStrings $cache)
    {
        /** @var CacheStrings $cache */
        foreach (self::KEYS as $key) {
            $this->assertFalse($cache->entryExists($key), "`$key`");
        }
    }
}
