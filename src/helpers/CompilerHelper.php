<?php
namespace VovanVE\HtmlTemplate\helpers;

use VovanVE\HtmlTemplate\compile\SyntaxException;
use VovanVE\HtmlTemplate\source\TemplateInterface;

class CompilerHelper
{
    const CONTEXT_CHARS = 30;

    /**
     * @param TemplateInterface $template
     * @param \VovanVE\parser\SyntaxException $e
     * @return SyntaxException
     */
    public static function buildSyntaxException($template, $e): SyntaxException
    {
        $code = $template->getContent();
        $name = $template->getName();
        $offset = $e->getOffset();
        $length = strlen($code);

        $after = $offset < $length
            ? substr($code, $offset, self::CONTEXT_CHARS)
            : '';
        if ($offset > 0) {
            $before_start = max(0, $offset - self::CONTEXT_CHARS);
            $before = substr($code, $before_start, $offset - $before_start);
        } else {
            $before = '';
        }

        $near = '' === $after
            ? ''
            : " near `$after`";
        $line = static::calcLineNumber($code, $offset);
        return new SyntaxException(
            $e->getMessage() . $near . " in `$name` at line $line",
            $line,
            $before,
            $after,
            $e
        );
    }

    /**
     * @param string $code
     * @param int $offset
     * @return int
     */
    public static function calcLineNumber($code, $offset): int
    {
        if (!$offset) {
            return 1;
        }

        $fragment = $offset < strlen($code)
            ? substr($code, 0, $offset)
            : $code;

        $n = preg_match_all('/\\R/u', $fragment);
        if (false !== $n) {
            return $n + 1;
        }

        $n = preg_match_all('/\\r\\n?|\\n/', $fragment);
        if (false !== $n) {
            return $n + 1;
        }

        return -1;
    }
}
