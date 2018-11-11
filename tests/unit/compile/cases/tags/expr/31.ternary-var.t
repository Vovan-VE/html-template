<a>{ lorem ? ipsum : dolor }</a>{'\n'}
<b>{ lorem ? ipsum : 'x > y' }</b>{'\n'}
<c>{ lorem ? 'x < y' : 'x > y' }</c>{'\n'}
<d>{ lorem ? 'x < y' : dolor }</d>{'\n'}
<e>{ !lorem ? ipsum : 'x > y' }</e>{'\n'}
<f>{ !lorem ? 'x < y' : 'x > y' }</f>{'\n'}
<g>{ !lorem ? 'x < y' : dolor }</g>
---- CODE ----
(($runtime::createElement('a',[],[($runtime::htmlEncode(($runtime::toString(((($runtime->param('lorem')))?(($runtime->param('ipsum'))):(($runtime->param('dolor'))))))))])).'
'.($runtime::createElement('b',[],[((($runtime->param('lorem')))?(($runtime::htmlEncode(($runtime::toString(($runtime->param('ipsum'))))))):('x &gt; y'))])).'
'.($runtime::createElement('c',[],[((($runtime->param('lorem')))?('x &lt; y'):('x &gt; y'))])).'
'.($runtime::createElement('d',[],[((($runtime->param('lorem')))?('x &lt; y'):(($runtime::htmlEncode(($runtime::toString(($runtime->param('dolor'))))))))])).'
'.($runtime::createElement('e',[],[(((!(($runtime->param('lorem')))))?(($runtime::htmlEncode(($runtime::toString(($runtime->param('ipsum'))))))):('x &gt; y'))])).'
'.($runtime::createElement('f',[],[(((!(($runtime->param('lorem')))))?('x &lt; y'):('x &gt; y'))])).'
'.($runtime::createElement('g',[],[(((!(($runtime->param('lorem')))))?('x &lt; y'):(($runtime::htmlEncode(($runtime::toString(($runtime->param('dolor'))))))))])))
---- RESULT ----
<a>[value of &amp;ipsum]</a>
<b>[value of &amp;ipsum]</b>
<c>x &lt; y</c>
<d>x &lt; y</d>
<e>x &gt; y</e>
<f>x &gt; y</f>
<g>[value of &amp;dolor]</g>
