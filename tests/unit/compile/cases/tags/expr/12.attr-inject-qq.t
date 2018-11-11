<div title={ "Lorem${ foo }ipsum&amp;" }></div>
---- CODE ----
($runtime::createElement('div',['title'=>('Lorem'.($runtime::toString(($runtime->param('foo')))).'ipsum&amp;')],[]))
---- RESULT ----
<div title="Lorem[value of &amp;foo]ipsum&amp;amp;"></div>
