HTML Template Changelog
=======================

0.0.3
-----

*   BC break: Add method `\VovanVE\HtmlTemplate\compile\CompilerInterface::getMeta()`.
*   BC break: Method `\VovanVE\HtmlTemplate\EngineInterface::runTemplate()` now receive
    optional `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface` in 2nd argument
    instead of just `array`.
*   BC break: Interface `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface` and
    implementation drop method `block($name): mixed` and introduce method
    `renderBlock($name): void` instead to output content instead of returning it.
*   BC break: `\Closure` to render a block will be called every time the block
    is rendered from template.
*   BC break: delete useless constant `\VovanVE\HtmlTemplate\Engine::VERSION`.

0.0.1
-----

First preview release.
