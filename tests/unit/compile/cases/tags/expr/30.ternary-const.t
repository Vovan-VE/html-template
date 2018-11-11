<a>{true ? 'then' : else}</a>{'\n'}
<b>{false ? then : 'else'}</b>{'\n'}
<c>{true ? then : else}</c>{'\n'}
<d>{false ? then : else}</d>
---- CODE ----
('<a>then</a>
<b>else</b>
'.($runtime::createElement('c',[],[($runtime::htmlEncode(($runtime::toString(($runtime->param('then'))))))])).'
'.($runtime::createElement('d',[],[($runtime::htmlEncode(($runtime::toString(($runtime->param('else'))))))])))
---- RESULT ----
<a>then</a>
<b>else</b>
<c>[value of &amp;then]</c>
<d>[value of &amp;else]</d>
