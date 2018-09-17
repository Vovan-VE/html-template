Lorem {{ %block foo }} ipsum
---- OK ----
Lorem <?php echo $runtime->block('foo') ?> ipsum
