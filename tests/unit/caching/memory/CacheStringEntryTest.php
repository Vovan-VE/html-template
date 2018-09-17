<?php
namespace VovanVE\HtmlTemplate\tests\unit\caching\memory;

use VovanVE\HtmlTemplate\caching\memory\CacheStringEntry;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;
use VovanVE\HtmlTemplate\tests\helpers\RuntimeCounter;

class CacheStringEntryTest extends BaseTestCase
{
    const NS = __CLASS__;
    const NAME = 'TempClass997';

    public function testBasic()
    {
        $class = self::NS . '\\' . self::NAME;

        $content = '<?php $runtime->didRun(); ?>';
        $meta = "String code HASH: " . md5($content) . "\n";

        $entry = new CacheStringEntry($class, $content, $meta);

        $this->assertEquals($class, $entry->getClassName(), 'class name');
        $this->assertEquals($content, $entry->getContent(), 'content');
        $this->assertEquals($meta, $entry->getMeta(), 'meta');

        $runtime = new RuntimeCounter();

        /** @noinspection PhpUnhandledExceptionInspection */
        $entry->run($runtime);
        /** @noinspection PhpUnhandledExceptionInspection */
        $entry->run($runtime);

        $this->assertEquals(2, $runtime->getRunsCount(), 'runs count');
    }
}
