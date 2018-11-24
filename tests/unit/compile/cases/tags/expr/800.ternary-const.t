<a>{true ? 'then' : else}</a>{'\n'}
<b>{false ? then : 'else'}</b>{'\n'}
<c>{true ? then : else}</c>{'\n'}
<d>{false ? then : else}</d>
---- CODE ----
('<a>then</a>
<b>else</b>
<c>'.($runtime::htmlEncode(($runtime::toString(($runtime->param('then')))))).'</c>
<d>'.($runtime::htmlEncode(($runtime::toString(($runtime->param('else')))))).'</d>')
---- RESULT ----
<a>then</a>
<b>else</b>
<c>[value of &amp;then]</c>
<d>[value of &amp;else]</d>
