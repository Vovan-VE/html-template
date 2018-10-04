<?php
namespace VovanVE\HtmlTemplate\runtime;

class RuntimeTemplateException extends \Exception
{
    /**
     * @var string
     * @since 0.3.1
     */
    protected $template;

    public function __construct(string $message = "", string $template = '', \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->template = $template;
    }

    /**
     * @return string
     * @since 0.3.1
     */
    public function getTemplate(): string
    {
        return $this->template;
    }
}
