<div title="Lorem{{ $foo }}ipsum"></div>
---- CODE ----
($runtime::createElement('div',['title'=>('Lorem' . ($runtime->param('foo')) . 'ipsum')],[]))
---- RESULT ----
<div title="Lorem[value of &amp;foo]ipsum"></div>
