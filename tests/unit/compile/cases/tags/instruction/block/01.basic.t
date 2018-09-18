Lorem {{ %block foo }} ipsum
---- OK ----
Lorem <?php $runtime->renderBlock('foo') ?> ipsum
