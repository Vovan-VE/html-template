<a>{ ( ( (( (('const'))  )) ) ) }</a>{'\n'}
<b>{ ( ( (( ((var))  )) ) ) }</b>{'\n'}
<c>{ ( ( (( ((<br/>))  )) ) ) }</c>
---- CODE ----
('<a>const</a>
<b>'.($runtime::htmlEncode(($runtime::toString(($runtime->param('var')))))).'</b>
<c><br/></c>')
---- RESULT ----
<a>const</a>
<b>[value of &amp;var]</b>
<c><br/></c>
