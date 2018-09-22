<div title='Lorem{{ $foo }}ipsum&#039;&quot;&amp;&lt;&gt;&rarr;'/>
---- OK ----
($runtime::createElement('div', ['title' => ('Lorem' . ($runtime->param('foo')) . 'ipsum\'"&<>→')]))
