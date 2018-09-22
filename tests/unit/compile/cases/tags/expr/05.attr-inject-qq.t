<div title="Lorem{{ $foo }}ipsum"></div>
---- OK ----
($runtime::createElement('div', ['title' => ('Lorem' . ($runtime->param('foo')) . 'ipsum')], []))
