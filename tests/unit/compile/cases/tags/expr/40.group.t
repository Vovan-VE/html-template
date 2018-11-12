<a>{ ( ( (( (('const'))  )) ) ) }</a>{'\n'}
<b>{ ( ( (( ((var))  )) ) ) }</b>
---- CODE ----
('<a>const</a>
<b>'.($runtime::htmlEncode(($runtime::toString(($runtime->param('var')))))).'</b>')
---- RESULT ----
<a>const</a>
<b>[value of &amp;var]</b>
