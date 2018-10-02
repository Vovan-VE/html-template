<?php
namespace VovanVE\HtmlTemplate\helpers;

use VovanVE\HtmlTemplate\compile\SyntaxException;
use VovanVE\HtmlTemplate\source\TemplateInterface;

class CompilerHelper
{
    const CONTEXT_CHARS = 30;

    private const CHARSET = 'UTF-8';

    /**
     * @param TemplateInterface $template
     * @param \VovanVE\parser\SyntaxException $e
     * @return SyntaxException
     */
    public static function buildSyntaxException(
        TemplateInterface $template,
        \VovanVE\parser\SyntaxException $e
    ): SyntaxException {
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
    public static function calcLineNumber(string $code, int $offset): int
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

    /**
     * @param string $content
     * @return string
     * @since 0.2.0
     */
    public static function htmlEncode(string $content): string
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, self::CHARSET);
    }

    /**
     * @param string $html
     * @return string
     * @since 0.2.0
     */
    public static function htmlDecodeEntity(string $html): string
    {
        return html_entity_decode($html, ENT_QUOTES | ENT_HTML5, self::CHARSET);
    }

    /**
     * @param int $code
     * @return string
     * @since 0.1.0
     */
    public static function utf8CharFromCode(int $code): string
    {
        if ($code >= 0) {
            if ($code <= 0x7F) {
                return chr($code);
            }
            if ($code <= 0x7FF) {
                return chr(0xC0 | $code >> 6) . chr(0x80 | $code & 0x3F);
            }
            if ($code <= 0xFFFF) {
                return chr(0xE0 | $code >> 12) . chr(0x80 | $code >> 6 & 0x3F) . chr(0x80 | $code & 0x3F);
            }
            if ($code <= 0x10FFFF) {
                return chr(0xF0 | $code >> 18) . chr(0x80 | $code >> 12 & 0x3F) . chr(0x80 | $code >> 6 & 0x3F) . chr(0x80 | $code & 0x3F);
            }
        }
        throw new \OutOfRangeException('Too big code - max is 0x10FFFF');
    }

    /**
     * @param string $name
     * @return bool
     * @since 0.1.0
     */
    public static function isComponentName(string $name): bool
    {
        return (bool)preg_match('/^[A-Z]/', $name);
    }

    /**
     * @param string $name
     * @return bool
     * @since 0.1.0
     */
    public static function isElementName(string $name): bool
    {
        return (bool)preg_match('/^[a-z][-a-z0-9:]*+$/D', $name);
    }
}
