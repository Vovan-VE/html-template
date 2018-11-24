<a>{ x || <i>x &lt; y</i> }</a>{'\n'}
<b>{ !x || <i>x &lt; y</i> }</b>{'\n'}
<c>{ <x/> || <y/> }</c>
---- CODE ----
('<a>'.((($_ta=(($runtime->param('x')))))?(($runtime::htmlEncode(($runtime::toString(($_ta)))))):('<i>x &lt; y</i>')).'</a>
<b>'.(((!(($runtime->param('x')))))?(''):('<i>x &lt; y</i>')).'</b>
<c><x/></c>')
---- RESULT ----
<a>[value of &amp;x]</a>
<b><i>x &lt; y</i></b>
<c><x/></c>
