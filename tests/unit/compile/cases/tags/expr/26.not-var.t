<a>{ !lorem ? 'then' : 'else' }</a>{'\n'}
<b>{ !!lorem ? 'then' : 'else' }</b>{'\n'}
<c>{ !!!lorem ? 'then' : 'else' }</c>{'\n'}
<d>{ !lorem }</d>{'\n'}
<e>{ !!lorem }</e>{'\n'}
<f>{ ! ! ! lorem }</f>
---- CODE ----
('<a>'.(((!(($runtime->param('lorem')))))?('then'):('else')).'</a>
<b>'.((((bool)(($runtime->param('lorem')))))?('then'):('else')).'</b>
<c>'.(((!(($runtime->param('lorem')))))?('then'):('else')).'</c>
<d></d>
<e></e>
<f></f>')
---- RESULT ----
<a>else</a>
<b>then</b>
<c>else</c>
<d></d>
<e></e>
<f></f>
