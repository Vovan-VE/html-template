HTML Template Changelog
=======================

0.1.0-dev
-----

*   **BC break**:
    *   Usage
        *   Change main concept of templates to be declarative instead of imperative.
        *   Drop support of `{{ %block name }}` instruction.
        *   Change template tags to single curly braces `{ ... }`.
        *   HTML and XML tags now will parse in XML mode. This means that block elements
            like `<div>` must be closed with corresponding end tags `</div>` with exactly
            the same case, and single elements must be in form like `<img/>`.
        *   HTML/XML attributes in elements now cannot be duplicated. Check is case sensitive.
        *   Work with UTF-8 charset only without any choice.
    *   API
        *   Delete: useless constant `\VovanVE\HtmlTemplate\Engine::VERSION`.
        *   Delete: method `block($name): mixed` from interface
            `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface` and its implementation.
        *   Delete: 2nd argument `$charset` from method `htmlEncode()` in interface
            `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface` and its implementation.
        *   Delete: 2nd argument `$blocks` from
            `\VovanVE\HtmlTemplate\runtime\RuntimeHelper` constructor.
        *   Delete: method `setBlocks()` from class 
            `\VovanVE\HtmlTemplate\runtime\RuntimeHelper`.
        *   Change: almost all methods now have type hinting for arguments.
        *   Change: method
            `runTemplate(string $name, ?RuntimeHelperInterface $runtime = null): string`
            in interface `\VovanVE\HtmlTemplate\EngineInterface` and its implementation
            now receive optional `RuntimeHelperInterface` in 2nd argument (instead of `array`)
            and returns result as `string` (instead of be `void` and output the result).
        *   Change: method `run(RuntimeHelperInterface $runtime): string`
            in interfaces `\VovanVE\HtmlTemplate\caching\CachedEntryInterface`
            and `\VovanVE\HtmlTemplate\runtime\RuntimeEntryDummyInterface`
            now has return type `string` (was `void`) and will return result instead of
            output it. Same is for theirs implementations.
        *   Add: method `getMeta(): string` to interface
            `\VovanVE\HtmlTemplate\compile\CompilerInterface` and its implementation.
        *   Add: method `checkTemplateSyntax(string $name): ReportInterface` to interface
            `\VovanVE\HtmlTemplate\EngineInterface` and its implementation.
        *   Add: static method `htmlDecodeEntity(string $html): string` to interface
            `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface` and its implementation.
        *   Add: static method
            `createElement(string $element, array $attributes = [], ?array $content = null): string`
            to interface `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface`
            and its implementation.
*   Add: `<!DOCTYPE...>` tag support.
*   Fix: broken `dash-case` names.

0.0.1
-----

First preview release.
