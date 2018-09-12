<?php
namespace VovanVE\HtmlTemplate\report;

interface ReportInterface
{
    /**
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @param int $minLevel
     * @return iterable|MessageInterface[]
     */
    public function getMessages($minLevel): iterable;

    /**
     * @param MessageInterface $message
     * @return void
     */
    public function addMessage($message): void;

    /**
     * @return void
     */
    public function clearMessages(): void;
}
