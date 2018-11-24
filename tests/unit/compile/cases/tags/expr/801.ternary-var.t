<a>{ lorem ? ipsum : dolor }</a>{'\n'}
<b>{ lorem ? ipsum : 'x > y' }</b>{'\n'}
<c>{ lorem ? 'x < y' : 'x > y' }</c>{'\n'}
<d>{ lorem ? 'x < y' : dolor }</d>{'\n'}
<e>{ !lorem ? ipsum : 'x > y' }</e>{'\n'}
<f>{ !lorem ? 'x < y' : 'x > y' }</f>{'\n'}
<g>{ !lorem ? 'x < y' : dolor }</g>
---- CODE ----
('<a>'.($runtime::htmlEncode(($runtime::toString(((($runtime->param('lorem')))?(($runtime->param('ipsum'))):(($runtime->param('dolor')))))))).'</a>
<b>'.((($runtime->param('lorem')))?(($runtime::htmlEncode(($runtime::toString(($runtime->param('ipsum'))))))):('x &gt; y')).'</b>
<c>'.((($runtime->param('lorem')))?('x &lt; y'):('x &gt; y')).'</c>
<d>'.((($runtime->param('lorem')))?('x &lt; y'):(($runtime::htmlEncode(($runtime::toString(($runtime->param('dolor')))))))).'</d>
<e>'.(((!(($runtime->param('lorem')))))?(($runtime::htmlEncode(($runtime::toString(($runtime->param('ipsum'))))))):('x &gt; y')).'</e>
<f>'.(((!(($runtime->param('lorem')))))?('x &lt; y'):('x &gt; y')).'</f>
<g>'.(((!(($runtime->param('lorem')))))?('x &lt; y'):(($runtime::htmlEncode(($runtime::toString(($runtime->param('dolor')))))))).'</g>')
---- RESULT ----
<a>[value of &amp;ipsum]</a>
<b>[value of &amp;ipsum]</b>
<c>x &lt; y</c>
<d>x &lt; y</d>
<e>x &gt; y</e>
<f>x &gt; y</f>
<g>[value of &amp;dolor]</g>
