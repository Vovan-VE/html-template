<div title={{ $foo }}></div>
---- CODE ----
($runtime::createElement('div',['title'=>($runtime->param('foo'))],[]))
---- RESULT ----
<div title="[value of &amp;foo]"></div>
