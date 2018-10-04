<?php
namespace VovanVE\HtmlTemplate\compile;

class SyntaxException extends CompileException
{
    /**
     * @var string
     * @since 0.3.1
     */
    protected $template;
    /** @var int */
    protected $errorLine;
    /** @var string */
    protected $before;
    /** @var string */
    protected $after;

    /**
     * @param string $message
     * @param string $template
     * @param int $errorLine
     * @param string $before
     * @param string $after
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = "",
        string $template = '',
        int $errorLine = 0,
        string $before = '',
        string $after = '',
        \Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->template = $template;
        $this->errorLine = $errorLine;
        $this->before = $before;
        $this->after = $after;
    }

    /**
     * @return string
     * @since 0.3.1
     */
    public function getTemplate()
    {
        return $this->template;
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
