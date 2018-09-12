<?php
namespace VovanVE\HtmlTemplate\report;

class Message implements MessageInterface
{
    /** @var int */
    private $level;
    /** @var string */
    private $message;
    /** @var string */
    private $file;
    /** @var int */
    private $line;

    private const LEVEL_STRING = [
        self::L_ERROR => 'Error',
        self::L_WARNING => 'Warning',
    ];

    /**
     * @param int $level
     * @param string $message
     * @param string $file
     * @param int $line
     */
    public function __construct($level, $message, $file = '', $line = 0)
    {
        $this->level = $level;
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getLevelString(): string
    {
        return self::LEVEL_STRING[$this->level] ?? 'L' . $this->level;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }
}
