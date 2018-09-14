<?php
namespace VovanVE\HtmlTemplate\tests\unit\report;

use VovanVE\HtmlTemplate\report\Message;
use VovanVE\HtmlTemplate\report\MessageInterface;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class MessageTest extends BaseTestCase
{
    public function testBasic()
    {
        $text = 'Message text';
        $line = 42;
        $error = new Message(MessageInterface::L_ERROR, $text, $line);
        $this->assertEquals(MessageInterface::L_ERROR, $error->getLevel(), 'level');
        $this->assertEquals('Error', $error->getLevelString(), 'level string');
        $this->assertEquals($text, $error->getMessage(), 'text');
        $this->assertEquals($line, $error->getLine(), 'line');

        $warning = new Message(MessageInterface::L_WARNING, '');
        $this->assertEquals('Warning', $warning->getLevelString());

        $unknown = new Message(997, '');
        $this->assertEquals('L997', $unknown->getLevelString());
    }
}
