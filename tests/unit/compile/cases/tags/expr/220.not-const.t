<a>{ !true ? 'then' : 'else' }</a>{'\n'}
<b>{ !'str' ? 'then' : 'else' }</b>{'\n'}
<c>{ !!true ? 'then' : 'else' }</c>{'\n'}
<d>{ !!'str' ? 'then' : 'else' }</d>{'\n'}
<e>{ !!!true ? 'then' : 'else' }</e>{'\n'}
<f>{ !!!'str' ? 'then' : 'else' }</f>{'\n'}
<g>{ !'str' }</g>{'\n'}
<h>{ !!'str' }</h>{'\n'}
<i>{ !!!'str' }</i>
---- CODE ----
'<a>else</a>
<b>else</b>
<c>then</c>
<d>then</d>
<e>else</e>
<f>else</f>
<g></g>
<h></h>
<i></i>'
---- RESULT ----
<a>else</a>
<b>else</b>
<c>then</c>
<d>then</d>
<e>else</e>
<f>else</f>
<g></g>
<h></h>
<i></i>
