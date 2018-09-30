HTML Template Changelog
=======================

0.2.0
-----

*   **BC break**:
    *   Usage:
        *   Change: execution order with components. Component's children now wrapped in
            a closure and will be evaluated only when component will do it. This allows
            you to use components for conditions and loops like `<IfSomething>...</IfSomething>`.
    *   API:
        *   Method `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface::createComponent()`
            changed its argument `$content` to `?\Closure` from `?array`;
        *   Method `\VovanVE\HtmlTemplate\components\ComponentInterface::render()`
            changed its arguments to `(RuntimeHelperInterface $runtime, ?\Closure $content = null)`
            from `(?array $content = null)`;
        *   Method `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface::htmlEncode()`
            remove `string` type hint for the only argument `$content`.
        *   Add methods to `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface` to create
            overridden copy:
            *   `addParams(array $params): RuntimeHelperInterface`
            *   `addComponents(array $components): RuntimeHelperInterface`
*   Deprecated:
    *   Method `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface::htmlDecodeEntity()`
        moved to internal `\VovanVE\HtmlTemplate\helpers\CompilerHelper::htmlDecodeEntity()`
        since its useless at runtime.
    *   Methods in `\VovanVE\HtmlTemplate\runtime\RuntimeHelper`:
        *   `setComponent()`.
        *   `setComponents()`.
        *   `setParams()`;
*   Add: component now can override `RuntimeHelperInterface` instance to render its content.
*   Enh: component now controls whether to render its content or not.

0.1.2
-----

*   Change: attribute duplication now detected just after attribute instead of
    after all attributes. Error message now will not contain element's name.
*   Enh: optimize generated code with constant evaluation at compile time.

0.1.1
-----

*   Add: Component definition now can be instance of:
    *   `\VovanVE\HtmlTemplate\components\ComponentSpawnerInterface` to create
        customized components at runtime;
    *   `\VovanVE\HtmlTemplate\components\ComponentInterface` too render ready
        component instance.

0.1.0
-----

*   **BC break**:
    *   Usage
        *   Change main concept of templates to be declarative instead of imperative.
        *   Drop support of `{{ %block name }}` instruction. Replaced with Components.
        *   Drop expression insertion in quoted HTML attributes like `x="...{{ $var }}..."`.
            Replaced with string literals like `x={"...${ var }..."}`.
        *   Change template tags to single curly braces `{ ... }`.
        *   Change variable syntax to just `name` instead of `$name`.
        *   HTML tags now will parse in XML mode. This means that block elements
            like `<div>` must be closed with corresponding end tags `</div>` with exactly
            the same case, and single elements must be in form like `<img/>`.
        *   HTML elements names now must be lowercase.
        *   HTML attributes in elements now cannot be duplicated. Check is case sensitive.
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
        *   Add: method
            `createComponent(string $name, array $properties = [], ?array $content = null): string`
            to interface `\VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface`
            and its implementation.
*   Add: string literals with possible expression injection in expression tags.
*   Add: `<!DOCTYPE...>` tag support.
*   Add: Components support to render custom markup.
*   Fix: broken `dash-case` names.

0.0.1
-----

First preview release.
