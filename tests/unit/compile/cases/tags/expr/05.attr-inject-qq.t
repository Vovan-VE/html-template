<div title="Lorem{{ $foo }}ipsum"></div>
---- OK ----
<div title="Lorem<?= $runtime::htmlEncode(($runtime->param('foo')), 'UTF-8') ?>ipsum"></div>
