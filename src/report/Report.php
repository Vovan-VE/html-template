<?php
namespace VovanVE\HtmlTemplate\report;

class Report implements ReportInterface
{
    /** @var array MessageInterface[][] */
    private $messages = [];

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return empty($this->messages[MessageInterface::L_ERROR]);
    }

    /**
     * @param int $minLevel
     * @return iterable|MessageInterface[]
     */
    public function getMessages($minLevel): iterable
    {
        foreach ($this->messages as $level => $messages) {
            if ($level <= $minLevel) {
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
