<a>{ x && <i title={title}>x &lt; y</i> }</a>{'\n'}
<b>{ !x && <i title={title}>x &lt; y</i> }</b>{'\n'}
<c>{ <x title={title}/> && <y title={title}/> }</c>
---- CODE ----
('<a>'.(((!(($_ta=(($runtime->param('x')))))))?(($runtime::htmlEncode(($runtime::toString(($_ta)))))):(($runtime::createElement('i',['title'=>($runtime->param('title'))],'x &lt; y')))).'</a>
<b>'.(((!((!(($runtime->param('x')))))))?(''):(($runtime::createElement('i',['title'=>($runtime->param('title'))],'x &lt; y')))).'</b>
<c>'.(!($_tb=($runtime::createElement('x',['title'=>($runtime->param('title'))])))?$_tb:(($runtime::createElement('y',['title'=>($runtime->param('title'))])))).'</c>')
---- RESULT ----
<a><i title="[value of &amp;title]">x &lt; y</i></a>
<b></b>
<c><y title="[value of &amp;title]"/></c>
