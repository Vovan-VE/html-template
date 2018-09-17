HTML Template
=============

[![Latest Stable Version](https://poser.pugx.org/vovan-ve/html-template/v/stable)](https://packagist.org/packages/vovan-ve/html-template)
[![Build Status](https://travis-ci.org/Vovan-VE/html-template.svg)](https://travis-ci.org/Vovan-VE/html-template)
[![License](https://poser.pugx.org/vovan-ve/html-template/license)](https://packagist.org/packages/vovan-ve/html-template)

Simple context sensitive HTML template engine. Yes, yet another.

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
?></span><span><?php echo $runtime->block('content') ?></span></a>
```

Description
-----------

TBW.

Installation
------------

Install through [composer][]:

    composer require vovan-ve/html-template

or add to `require` section in your composer.json:

    "vovan-ve/html-template": "~0.0.1"

License
-------

This package is under [MIT License][mit]


[composer]: http://getcomposer.org/
[mit]: https://opensource.org/licenses/MIT
