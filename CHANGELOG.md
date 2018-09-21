HTML Template Changelog
=======================

0.1.0-dev
-----

*   **BC break**:
    *   Delete useless constant `\VovanVE\HtmlTemplate\Engine::VERSION`.
    *   Almost all methods now has type hinting for arguments.
    *   Interface `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface` and
        implementation did delete method `block($name): mixed` and introduce new method
        `renderBlock(string $name): void` instead to output content instead of returning it.
    *   Method `\VovanVE\HtmlTemplate\EngineInterface::runTemplate()` now receive
        optional `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface` in 2nd argument
        instead of just `array`.
    *   Add method `\VovanVE\HtmlTemplate\compile\CompilerInterface::getMeta(): string`.
    *   Add method `checkTemplateSyntax(string $name): ReportInterface` to interface
        `\VovanVE\HtmlTemplate\EngineInterface` and its implementation.
    *   `\Closure` to render a block will be called every time the block
        is rendered from template.
    *   HTML and XML tags now will parse in XML mode. This means that block elements
        like `<div>` must be closed with corresponding end tags `</div>` with exactly
        the same case, and single elements must be in form like `<img/>`.
*   Add: `<!DOCTYPE...>` tag support.

0.0.1
-----

First preview release.
