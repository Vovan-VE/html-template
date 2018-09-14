<?php
namespace VovanVE\HtmlTemplate\tests\unit\compile;

use VovanVE\HtmlTemplate\compile\CompiledEntry;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class CompiledEntryTest extends BaseTestCase
{
    public function testBasic()
    {
        $content = 'Lorem ipsum dolor';

        $entry = new CompiledEntry($content);

        $this->assertEquals($content, $entry->getContent());
    }
}
