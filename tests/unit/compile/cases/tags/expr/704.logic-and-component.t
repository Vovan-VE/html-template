<a>{ x && <TestComponent>x &lt; y</TestComponent> }</a>{'\n'}
<b>{ !x && <TestComponent>x &lt; y</TestComponent> }</b>{'\n'}
<c>{ <TestComponent foo="x"/> && <TestComponent foo="y"/> }</c>
---- CODE ----
('<a>'.(((!(($_ta=(($runtime->param('x')))))))?(($runtime::htmlEncode(($runtime::toString(($_ta)))))):(($runtime->createComponent('TestComponent',[],function($runtime){return 'x &lt; y';})))).'</a>
<b>'.(((!((!(($runtime->param('x')))))))?(''):(($runtime->createComponent('TestComponent',[],function($runtime){return 'x &lt; y';})))).'</b>
<c>'.(!($_tb=($runtime->createComponent('TestComponent',['foo'=>'x'])))?$_tb:(($runtime->createComponent('TestComponent',['foo'=>'y'])))).'</c>')
---- RESULT ----
<a><!-- Test Component: foo=null bar=null -->x &lt; y<!-- /Test Component --></a>
<b></b>
<c><!-- Test Component: foo="y" bar=null /--></c>
