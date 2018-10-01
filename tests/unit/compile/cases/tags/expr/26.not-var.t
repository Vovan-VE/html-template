<a>{ !lorem ? 'then' : 'else' }</a>{'\n'}
<b>{ !!lorem ? 'then' : 'else' }</b>{'\n'}
<c>{ !!!lorem ? 'then' : 'else' }</c>{'\n'}
<d>{ !lorem }</d>{'\n'}
<e>{ !!lorem }</e>{'\n'}
<f>{ ! ! ! lorem }</f>
---- CODE ----
(($runtime::createElement('a',[],[($runtime::htmlEncode((((!(($runtime->param('lorem')))))?('then'):('else'))))])).'
'.($runtime::createElement('b',[],[($runtime::htmlEncode(((((bool)(($runtime->param('lorem')))))?('then'):('else'))))])).'
'.($runtime::createElement('c',[],[($runtime::htmlEncode((((!(($runtime->param('lorem')))))?('then'):('else'))))])).'
'.($runtime::createElement('d',[],[($runtime::htmlEncode((!(($runtime->param('lorem'))))))])).'
'.($runtime::createElement('e',[],[($runtime::htmlEncode(((bool)(($runtime->param('lorem'))))))])).'
'.($runtime::createElement('f',[],[($runtime::htmlEncode((!(($runtime->param('lorem'))))))])))
---- RESULT ----
<a>else</a>
<b>then</b>
<c>else</c>
<d></d>
<e></e>
<f></f>
