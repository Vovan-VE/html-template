<a>{ true || true || 'fail' }</a>{'\n'}
<b>{ true || false || 'fail' }</b>{'\n'}
<c>{ false || true || 'fail' }</c>{'\n'}
<d>{ false || false || 'ok' }</d>{'\n'}
<e>{ false || (false || 'ok') }</e>
---- CODE ----
'<a></a>
<b></b>
<c></c>
<d>ok</d>
<e>ok</e>'
---- RESULT ----
<a></a>
<b></b>
<c></c>
<d>ok</d>
<e>ok</e>
