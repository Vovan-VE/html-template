HTML Template
=============

[![Latest Stable Version](https://img.shields.io/packagist/v/vovan-ve/html-template.svg)](https://packagist.org/packages/vovan-ve/html-template)
[![Build Status](https://travis-ci.org/Vovan-VE/html-template.svg)](https://travis-ci.org/Vovan-VE/html-template)
[![License](https://poser.pugx.org/vovan-ve/html-template/license)](https://packagist.org/packages/vovan-ve/html-template)

Simple context sensitive HTML template engine. Yes, yet another one.

Synopsis
--------

See [an example](./examples/01.basics.php).

Template example:

```html
<a href={{$link}} title="Foo bar: {{ $title }}">
    <span id=foobar class='it parses html'>
        {{ $description }}
    </span>
    <span>
        {{ %block content }}
    </span>
</a>
```

Compiled code (wrapped manually only here):

```php
<a href="<?= $runtime::htmlEncode(($runtime->param('link')), 'UTF-8')
?>" title="Foo bar: <?= $runtime::htmlEncode(($runtime->param('title')), 'UTF-8')
?>"><span id="foobar" class="it parses html"><?= $runtime::htmlEncode(($runtime->param('description')), 'UTF-8')
?></span><span><?php $runtime->renderBlock('content') ?></span></a>
```

Creating data for the example template above:

```php
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;

$runtime = (new RuntimeHelper)
    // params are always text/plain
    ->setParams([
        'link' => 'http://example.com?foo=bar&lorem=ipsum#hash',
        'title' => 'Lorem <ipsum> "dolor" sit amet',
        // Closure will execute only once to obtain its return value
        'description' => function () {
            return 'Some <text/plain> content';
        },
    ])
    // blocks are text/html
    ->setBlocks([
        'content' => "<samp>content</samp>'s block content<br/>is a HTML",
    ]);
```

Run a template when everything is prepared. Here `foobar` is a template's name
covered by Template Provider.

```php
$engine->runTemplate('foobar', $runtime);
```

The output for the example above (wrapped manually only here):

```html
<a href="http://example.com?foo=bar&amp;lorem=ipsum#hash"
title="Foo bar: Lorem &lt;ipsum&gt; &quot;dolor&quot; sit amet"
><span id="foobar" class="it parses html"
>Some &lt;text/plain&gt; content</span><span
><samp>content</samp>'s block content<br/>is a HTML</span></a>
```

Description
-----------

TBW.

Installation
------------

Install through [composer][]:

    composer require vovan-ve/html-template

or add to `require` section in your composer.json:

    "vovan-ve/html-template": "~0.0.3"

License
-------

This package is under [MIT License][mit]


[composer]: http://getcomposer.org/
[mit]: https://opensource.org/licenses/MIT
