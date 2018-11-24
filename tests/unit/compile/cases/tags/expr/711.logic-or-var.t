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
<d>'.($runtime::htmlEncode(($runtime::toString(($runtime->param('ok')))))).'</d>
<e>'.($runtime::htmlEncode(($runtime::toString(($runtime->param('ok')))))).'</e>
<f>'.($runtime::htmlEncode(($runtime::toString(((($runtime->param('lorem')))?:(((($runtime->param('ipsum')))?:(($runtime->param('dolor')))))))))).'</f>')
---- RESULT ----
<a></a>
<b></b>
<c></c>
<d>[value of &amp;ok]</d>
<e>[value of &amp;ok]</e>
<f>[value of &amp;lorem]</f>
