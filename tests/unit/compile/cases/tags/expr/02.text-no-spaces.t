lorem{{$foo}}ipsum
---- OK ----
('lorem' . ($runtime::htmlEncode(($runtime->param('foo')))) . 'ipsum')
