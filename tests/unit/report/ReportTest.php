<?php
namespace VovanVE\HtmlTemplate\tests\unit\report;

use VovanVE\HtmlTemplate\report\Message;
use VovanVE\HtmlTemplate\report\MessageInterface;
use VovanVE\HtmlTemplate\report\Report;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class ReportTest extends BaseTestCase
{
    public function testBasic()
    {
        $file = 'source.txt.tmp';

        $report = new Report($file);

        $this->assertEquals($file, $report->getFile(), 'file');

        $this->assertTrue($report->isSuccess(), 'empty is success');
        $this->assertCount(0, $report->getMessages());

        $report->addMessage(new Message(MessageInterface::L_WARNING, 'A warning message', 42));
        $report->addMessage(new Message(MessageInterface::L_WARNING, 'Another warning', 37));

        $this->assertTrue($report->isSuccess(), 'only warnings is success');
        $this->assertCount(2, $report->getMessages());

        $report->addMessage(new Message(MessageInterface::L_ERROR, 'Failure', 23));

        $this->assertFalse($report->isSuccess(), 'any error is failure');
        $this->assertCount(3, $report->getMessages());

        $report->clearMessages();

        $this->assertTrue($report->isSuccess(), 'empty is success');
        $this->assertCount(0, $report->getMessages());
    }
}
