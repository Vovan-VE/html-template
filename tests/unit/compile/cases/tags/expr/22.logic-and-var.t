<a>{ true && true && ok }</a>{'\n'}
<b>{ true && false && fail }</b>{'\n'}
<c>{ false && true && fail }</c>{'\n'}
<d>{ false && false && fail }</d>{'\n'}
<e>{ true && (true && ok) }</e>{'\n'}
<f>{ lorem && ipsum && dolor }</f>{'\n'}
<g>{ lorem && (ipsum && dolor) }</g>
---- CODE ----
('<a>'.($runtime::htmlEncode(($runtime::toString(($runtime->param('ok')))))).'</a>
<b></b>
<c></c>
<d></d>
<e>'.($runtime::htmlEncode(($runtime::toString(($runtime->param('ok')))))).'</e>
<f>'.($runtime::htmlEncode(($runtime::toString((!($_tb=($runtime->param('lorem')))?$_tb:((!($_ta=($runtime->param('ipsum')))?$_ta:(($runtime->param('dolor')))))))))).'</f>
<g>'.($runtime::htmlEncode(($runtime::toString((!($_td=($runtime->param('lorem')))?$_td:((!($_tc=($runtime->param('ipsum')))?$_tc:(($runtime->param('dolor')))))))))).'</g>')
---- RESULT ----
<a>[value of &amp;ok]</a>
<b></b>
<c></c>
<d></d>
<e>[value of &amp;ok]</e>
<f>[value of &amp;dolor]</f>
<g>[value of &amp;dolor]</g>
