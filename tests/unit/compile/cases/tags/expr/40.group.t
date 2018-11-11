<a>{ ( ( (( (('const'))  )) ) ) }</a>{'\n'}
<b>{ ( ( (( ((var))  )) ) ) }</b>
---- CODE ----
('<a>const</a>
'.($runtime::createElement('b',[],[($runtime::htmlEncode(($runtime::toString(($runtime->param('var'))))))])))
---- RESULT ----
<a>const</a>
<b>[value of &amp;var]</b>
