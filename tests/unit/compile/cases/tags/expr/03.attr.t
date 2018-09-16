<div title={{ $foo }}>
---- OK ----
<div title="<?= $runtime::htmlEncode(($runtime->param('foo')), 'UTF-8') ?>">
