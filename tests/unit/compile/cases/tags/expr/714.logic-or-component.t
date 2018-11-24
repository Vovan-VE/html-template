<a>{ x || <TestComponent>x &lt; y</TestComponent> }</a>{'\n'}
<b>{ !x || <TestComponent>x &lt; y</TestComponent> }</b>{'\n'}
<c>{ <TestComponent foo="x"/> || <TestComponent foo="y"/> }</c>
---- CODE ----
('<a>'.((($_ta=(($runtime->param('x')))))?(($runtime::htmlEncode(($runtime::toString(($_ta)))))):(($runtime->createComponent('TestComponent',[],function($runtime){return 'x &lt; y';})))).'</a>
<b>'.(((!(($runtime->param('x')))))?(''):(($runtime->createComponent('TestComponent',[],function($runtime){return 'x &lt; y';})))).'</b>
<c>'.((($runtime->createComponent('TestComponent',['foo'=>'x'])))?:(($runtime->createComponent('TestComponent',['foo'=>'y'])))).'</c>')
---- RESULT ----
<a>[value of &amp;x]</a>
<b><!-- Test Component: foo=null bar=null -->x &lt; y<!-- /Test Component --></b>
<c><!-- Test Component: foo="x" bar=null /--></c>
