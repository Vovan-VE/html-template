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
*   value -> getFinalValue()
    *   temp var assignment with unused var will return origin inner value
