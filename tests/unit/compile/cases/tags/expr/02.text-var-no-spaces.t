lorem{foo}ipsum
---- CODE ----
('lorem' . ($runtime::htmlEncode(($runtime->param('foo')))) . 'ipsum')
---- RESULT ----
lorem[value of &amp;foo]ipsum
