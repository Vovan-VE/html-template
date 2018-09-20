<div title={{ $foo }}></div>
---- OK ----
<div title="<?= $runtime::htmlEncode(($runtime->param('foo')), 'UTF-8') ?>"></div>
