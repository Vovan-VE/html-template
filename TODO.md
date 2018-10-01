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
*   Optimization: make `htmlEncode()` a filter (deterministic) and let them (filters)
    to sink into const branches in general:
    *   `html(concat(const,const,var,...))` => `concat(const_,const_,html(var),...)`
    *   `html(var ? const : const)` => `var ? const_ : const_`
