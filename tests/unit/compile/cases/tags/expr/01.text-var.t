lorem  { foo }  ipsum
---- CODE ----
('lorem  '.($runtime::htmlEncode(($runtime::toString(($runtime->param('foo')))))).'  ipsum')
---- RESULT ----
lorem  [value of &amp;foo]  ipsum
