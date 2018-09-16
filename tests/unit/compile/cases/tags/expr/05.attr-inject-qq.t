<div title="Lorem{{ $foo }}ipsum">
---- OK ----
<div title="Lorem<?= $runtime::htmlEncode(($runtime->param('foo')), 'UTF-8') ?>ipsum">
