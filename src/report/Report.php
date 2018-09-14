<?php
namespace VovanVE\HtmlTemplate\report;

class Report implements ReportInterface
{
    /** @var string */
    private $file;
    /** @var array MessageInterface[][] */
    private $messages = [];

    /**
     * @param string $file
     */
    public function __construct($file = '')
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return empty($this->messages[MessageInterface::L_ERROR]);
    }

    /**
     * @param int|null $minLevel
     * @return iterable|MessageInterface[]
     */
    public function getMessages($minLevel = null): iterable
    {
        foreach ($this->messages as $level => $messages) {
            if (null === $minLevel || $level <= $minLevel) {
                foreach ($messages as $message) {
                    yield $message;
                }
            }
        }
    }

    /**
     * @param MessageInterface $message
     * @return void
     */
    public function addMessage($message): void
    {
        $level = $message->getLevel();
        if (isset($this->messages[$level])) {
            $this->messages[$level][] = $message;
        } else {
            $this->messages[$level] = [$message];
            ksort($this->messages);
        }
    }

    /**
     * @return void
     */
    public function clearMessages(): void
    {
        $this->messages = [];
    }
}
