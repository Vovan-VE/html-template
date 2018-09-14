<?php
namespace VovanVE\HtmlTemplate\report;

interface MessageInterface
{
    const L_ERROR = 10;
    const L_WARNING = 20;

    /**
     * @return int
     */
    public function getLevel(): int;

    /**
     * @return string
     */
    public function getLevelString(): string;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return int
     */
    public function getLine(): int;
}
