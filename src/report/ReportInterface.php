<?php
namespace VovanVE\HtmlTemplate\report;

interface ReportInterface
{
    /**
     * @return string
     */
    public function getFile(): string;

    /**
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @param int|null $minLevel
     * @return iterable|MessageInterface[]
     */
    public function getMessages(?int $minLevel = null): iterable;

    /**
     * @param MessageInterface $message
     * @return void
     */
    public function addMessage(MessageInterface $message): void;

    /**
     * @return void
     */
    public function clearMessages(): void;
}
