HTML Template
=============

[![Latest Stable Version](https://img.shields.io/packagist/v/vovan-ve/html-template.svg)](https://packagist.org/packages/vovan-ve/html-template)
[![Latest Dev Version](https://img.shields.io/packagist/vpre/vovan-ve/html-template.svg)](https://packagist.org/packages/vovan-ve/html-template)
[![Build Status](https://travis-ci.org/Vovan-VE/html-template.svg)](https://travis-ci.org/Vovan-VE/html-template)
[![License](https://poser.pugx.org/vovan-ve/html-template/license)](https://packagist.org/packages/vovan-ve/html-template)

Simple context sensitive HTML template engine. Yes, yet another one.

Synopsis
--------

See [an examples](./examples/).

Template example:

```
<a href={ link } title={ title && "Foo bar: ${ title }" }>
    <span id=foobar class='it parses html'>
        { description }
    </span>
    <span>
        {# comment #}
    </span>
</a>
```

Creating data for the template above:

```php
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;

$runtime = new RuntimeHelper([
    'link' => 'http://example.com?foo=bar&lorem=ipsum#hash',
    'title' => 'Lorem <ipsum> "dolor" sit amet',
    'description' => function () {
        return 'Some <text/plain> content';
    },
]);
```

Run a template when everything is prepared. Here `foobar` is a template's name
covered by Template Provider.

```php
echo $engine->runTemplate('foobar', $runtime);
```

The output for the example above (wrapped manually only here):

```html
<a href="http://example.com?foo=bar&amp;lorem=ipsum#hash"
title="Foo bar: Lorem &lt;ipsum&gt; &quot;dolor&quot; sit amet"
><span id="foobar" class="it parses html"
>Some &lt;text/plain&gt; content</span><span></span></a>
```

Template code compiles to PHP code behind the scene. It may look
something like so (formatted manually only here for demonstration):

```php
($runtime::createElement('a', [
    'href'  => ($runtime->param('link')),
    'title' => (!($_ta=($runtime->param('title')))
        ? $_ta
        :(('Foo bar: '.($runtime->param('title'))))
    )
], [
    ($runtime::createElement('span', [
        'id'    => 'foobar',
        'class' => 'it parses html'
    ], [
        ($runtime::htmlEncode(($runtime->param('description'))))
    ])),
    '<span></span>'
]))
```

Compiler will evaluate as much constant expressions as possible
and as much as it learned to. For example, completely constant template
like following:

```
<div>{true && 'Lorem < ipsum dolor'}</div>
```

will be compiled to:

```php
'<div>Lorem &lt; ipsum dolor</div>'
```

Description
-----------

TBW.

Installation
------------

> **ATTENTION!** While major version number is `0` still there MAY be
> BC break changes in minor versions `0.NEXT`, but not in revision
> versions `0.x.NEXT`.

Install through [composer][]:

    composer require vovan-ve/html-template

or add to `require` section in your composer.json:

    "vovan-ve/html-template": "~0.4.0"

License
-------

This package is under [MIT License][mit]


[composer]: http://getcomposer.org/
[mit]: https://opensource.org/licenses/MIT
