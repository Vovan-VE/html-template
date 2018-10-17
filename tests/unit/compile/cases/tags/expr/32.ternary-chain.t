<a>{true ? '1' : true ? '2' : true ? '3' : '4'}</a>{'\n'}
<b>{true ? '' : true ? '2' : true ? '3' : '4'}</b>{'\n'}
<c>{false ? '1' : true ? '2' : true ? '3' : '4'}</c>{'\n'}
<d>{false ? '1' : true ? '' : true ? '3' : '4'}</d>{'\n'}
<e>{false ? '1' : false ? '2' : true ? '3' : '4'}</e>{'\n'}
<f>{false ? '1' : false ? '2' : false ? '3' : '4'}</f>
---- CODE ----
'<a>1</a>
<b></b>
<c>2</c>
<d></d>
<e>3</e>
<f>4</f>'
---- RESULT ----
<a>1</a>
<b></b>
<c>2</c>
<d></d>
<e>3</e>
<f>4</f>
