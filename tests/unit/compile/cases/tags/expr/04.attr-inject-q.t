<div title='Lorem{{ $foo }}ipsum&#039;&quot;&amp;&lt;&gt;&rarr;'/>
---- CODE ----
($runtime::createElement('div', ['title' => ('Lorem' . ($runtime->param('foo')) . 'ipsum\'"&<>→')]))
---- RESULT ----
<div title="Lorem[value of &amp;foo]ipsum&#039;&quot;&amp;&lt;&gt;→"/>
