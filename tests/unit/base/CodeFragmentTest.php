<?php
namespace VovanVE\HtmlTemplate\tests\unit\base;

use VovanVE\HtmlTemplate\base\CodeFragment;
use VovanVE\HtmlTemplate\base\CodeFragmentInterface;
use VovanVE\HtmlTemplate\tests\helpers\BaseTestCase;

class CodeFragmentTest extends BaseTestCase
{
    public function testFetchOnce()
    {
        /** @var CodeFragmentInterface $fragment */
        $fragment = new class extends CodeFragment {
            private $counter = 0;
            protected function fetchContent(): string
            {
                return (string)++$this->counter;
            }
        };

        $this->assertEquals('1', $fragment->getContent(), 'initiate');
        $this->assertEquals('1', $fragment->getContent(), 'repeat');
    }

    public function testPredefined()
    {
        $content = 'some string';

        /** @var CodeFragmentInterface $fragment */
        $fragment = new class($content) extends CodeFragment {
            /**
             * @param string $content
             */
            public function __construct(string $content)
            {
                parent::__construct();
                $this->content = $content;
            }

            protected function fetchContent(): string
            {
                throw new \RuntimeException('Should not be called');
            }
        };

        $this->assertEquals($content, $fragment->getContent(), 'no fetching');
    }
}
