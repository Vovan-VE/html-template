<?php
namespace VovanVE\HtmlTemplate\compile;

class SyntaxException extends CompileException
{
    /** @var int */
    protected $errorLine;
    /** @var string */
    protected $before;
    /** @var string */
    protected $after;

    /**
     * @param string $message
     * @param int $errorLine
     * @param string $before
     * @param string $after
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = "",
        int $errorLine = 0,
        string $before = '',
        string $after = '',
        \Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->errorLine = $errorLine;
        $this->before = $before;
        $this->after = $after;
    }

    /**
     * @return int
     */
    public function getErrorLine()
    {
        return $this->errorLine;
    }

    /**
     * @return string
     */
    public function getContextBefore()
    {
        return $this->before;
    }

    /**
     * @return string
     */
    public function getContextAfter()
    {
        return $this->after;
    }
}
