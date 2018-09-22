<?php
namespace VovanVE\HtmlTemplate\tests\unit\caching;

use VovanVE\HtmlTemplate\caching\CacheEntry;
use VovanVE\HtmlTemplate\runtime\RuntimeEntryDummyInterface;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;
use VovanVE\HtmlTemplate\tests\helpers\RuntimeCounter;

class CacheEntryTest extends BaseTestCase
{
    public function testRuns()
    {
        $className = __CLASS__ . '\\_at_' . __FUNCTION__ . '_997';

        $entry = new class($className) extends CacheEntry {
            private $code;

            public function __construct(string $className)
            {
                parent::__construct($className);

                $p = strrpos($className, '\\');
                $ns = substr($className, 0, $p);
                $name = substr($className, $p + 1);

                /** @uses RuntimeEntryDummyInterface::run() */
                /** @uses RuntimeCounter::didRun() */
                $this->code =
                    "namespace $ns;\n" .
                    "class $name {\n" .
                    "    public static function run(\$runtime): string {\n" .
                    "        return (string)\$runtime->didRun();\n" .
                    "    }\n" .
                    "}";
            }

            /**
             * @return void
             */
            protected function declareClass(): void
            {
                eval($this->code);
            }

            /**
             * @return string|null
             */
            public function getMeta(): ?string
            {
                return "Temp class from " . __METHOD__ . "()\n";
            }

            /**
             * @return string
             */
            protected function fetchContent(): string
            {
                return $this->code;
            }
        };

        $runtime = new RuntimeCounter();

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals('1', $entry->run($runtime));
        $this->assertEquals(1, $runtime->getRunsCount());

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals('2', $entry->run($runtime));
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals('3', $entry->run($runtime));

        $this->assertEquals(3, $runtime->getRunsCount());
    }
}
