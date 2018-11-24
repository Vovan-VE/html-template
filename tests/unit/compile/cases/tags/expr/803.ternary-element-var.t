<a>{ true ? <div title={title}>x &lt; y</div> : <div title={title}>x &gt; y</div> }</a>{'\n'}
<b>{ false ? <div title={title}>x &lt; y</div> : <div title={title}>x &gt; y</div> }</b>{'\n'}
<c>{ x ? <div title={title}>x &lt; y</div> : <div title={title}>x &gt; y</div> }</c>{'\n'}
<d>{ !x ? <div title={title}>x &lt; y</div> : <div title={title}>x &gt; y</div> }</d>{'\n'}
<e>{ <div title={title}/> ? 'true' : 'false' }</e>{'\n'}
<f>{ !<div title={title}/> ? 'true' : 'false' }</f>{'\n'}
<g>{ <TestComponent/> ? 'true' : 'false' }</g>{'\n'}
<h>{ !<TestComponent/> ? 'true' : 'false' }</h>
---- CODE ----
('<a>'.($runtime::createElement('div',['title'=>($runtime->param('title'))],'x &lt; y')).'</a>
<b>'.($runtime::createElement('div',['title'=>($runtime->param('title'))],'x &gt; y')).'</b>
<c>'.((($runtime->param('x')))?(($runtime::createElement('div',['title'=>($runtime->param('title'))],'x &lt; y'))):(($runtime::createElement('div',['title'=>($runtime->param('title'))],'x &gt; y')))).'</c>
<d>'.(((!(($runtime->param('x')))))?(($runtime::createElement('div',['title'=>($runtime->param('title'))],'x &lt; y'))):(($runtime::createElement('div',['title'=>($runtime->param('title'))],'x &gt; y')))).'</d>
<e>true</e>
<f>false</f>
<g>'.((($runtime->createComponent('TestComponent')))?('true'):('false')).'</g>
<h>'.(((!(($runtime->createComponent('TestComponent')))))?('true'):('false')).'</h>')
---- RESULT ----
<a><div title="[value of &amp;title]">x &lt; y</div></a>
<b><div title="[value of &amp;title]">x &gt; y</div></b>
<c><div title="[value of &amp;title]">x &lt; y</div></c>
<d><div title="[value of &amp;title]">x &gt; y</div></d>
<e>true</e>
<f>false</f>
<g>true</g>
<h>false</h>
