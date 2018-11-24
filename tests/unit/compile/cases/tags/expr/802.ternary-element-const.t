<a>{ true ? <div>x &lt; y</div> : <div>x &gt; y</div> }</a>{'\n'}
<b>{ false ? <div>x &lt; y</div> : <div>x &gt; y</div> }</b>{'\n'}
<c>{ x ? <div>x &lt; y</div> : <div>x &gt; y</div> }</c>{'\n'}
<d>{ !x ? <div>x &lt; y</div> : <div>x &gt; y</div> }</d>{'\n'}
<e>{ <div/> ? 'true' : 'false' }</e>{'\n'}
<f>{ !<div/> ? 'true' : 'false' }</f>{'\n'}
<g>{ <TestComponent/> ? 'true' : 'false' }</g>{'\n'}
<h>{ !<TestComponent/> ? 'true' : 'false' }</h>
---- CODE ----
('<a><div>x &lt; y</div></a>
<b><div>x &gt; y</div></b>
<c>'.((($runtime->param('x')))?('<div>x &lt; y</div>'):('<div>x &gt; y</div>')).'</c>
<d>'.(((!(($runtime->param('x')))))?('<div>x &lt; y</div>'):('<div>x &gt; y</div>')).'</d>
<e>true</e>
<f>false</f>
<g>'.((($runtime->createComponent('TestComponent')))?('true'):('false')).'</g>
<h>'.(((!(($runtime->createComponent('TestComponent')))))?('true'):('false')).'</h>')
---- RESULT ----
<a><div>x &lt; y</div></a>
<b><div>x &gt; y</div></b>
<c><div>x &lt; y</div></c>
<d><div>x &gt; y</div></d>
<e>true</e>
<f>false</f>
<g>true</g>
<h>false</h>
