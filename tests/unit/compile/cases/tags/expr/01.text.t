lorem  {{ $foo }}  ipsum
---- OK ----
lorem  <?= $runtime::htmlEncode(($runtime->param('foo')), 'UTF-8') ?>  ipsum
