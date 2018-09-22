<div title={{ $foo }}></div>
---- OK ----
($runtime::createElement('div', ['title' => ($runtime->param('foo'))], []))
