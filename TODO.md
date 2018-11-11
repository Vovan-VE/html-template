TODO
====

*   `CacheInterface::deleteAllEntries()`
*   Compile-time check around components:
    *   is defined
    *   has props
    *   allows/requires content
*   Rename `RuntimeHelper` to something else.
*   `RuntimeHelperInterface`:
    *   `removeParams()`
    *   `removeAllParams()`
    *   `removeComponents()`
    *   `removeAllComponents()`
*   HtmlElement as expression: `{ expr && <div/> }`
    *   Optimize: `createElement(const, const[], var)` => `<const>...</const>`
