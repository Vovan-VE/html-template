<a>{ true || true || fail }</a>{'\n'}
<b>{ true || false || fail }</b>{'\n'}
<c>{ false || true || fail }</c>{'\n'}
<d>{ false || false || ok }</d>{'\n'}
<e>{ false || (false || ok) }</e>{'\n'}
<f>{ lorem || ipsum || dolor }</f>
---- CODE ----
('<a></a>
<b></b>
<c></c>
'.($runtime::createElement('d',[],[($runtime::htmlEncode(($runtime->param('ok'))))])).'
'.($runtime::createElement('e',[],[($runtime::htmlEncode(($runtime->param('ok'))))])).'
'.($runtime::createElement('f',[],[($runtime::htmlEncode(((($runtime->param('lorem')))?:(((($runtime->param('ipsum')))?:(($runtime->param('dolor'))))))))])))
---- RESULT ----
<a></a>
<b></b>
<c></c>
<d>[value of &amp;ok]</d>
<e>[value of &amp;ok]</e>
<f>[value of &amp;lorem]</f>
