<a>{ true && true && 'ok' }</a>{'\n'}
<b>{ true && false && 'fail' }</b>{'\n'}
<c>{ false && true && 'fail' }</c>{'\n'}
<d>{ false && false && 'fail' }</d>{'\n'}
<e>{ true && (true && 'ok') }</e>
---- CODE ----
'<a>ok</a>
<b></b>
<c></c>
<d></d>
<e>ok</e>'
---- RESULT ----
<a>ok</a>
<b></b>
<c></c>
<d></d>
<e>ok</e>
