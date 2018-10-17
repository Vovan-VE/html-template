<a>{true ? true ? '1' : '2' : true ? '3' : '4'}</a>{'\n'}
<b>{true ? true ? '1' : '2' : true ? '3' : ''}</b>{'\n'}
<c>{true ? true ? '1' : '2' : true ? '' : '4'}</c>{'\n'}
<d>{true ? true ? '1' : '2' : false ? '3' : '4'}</d>{'\n'}
<e>{true ? true ? '1' : '' : true ? '3' : '4'}</e>{'\n'}
<f>{true ? true ? '' : '2' : true ? '3' : '4'}</f>{'\n'}
<g>{true ? false ? '1' : '2' : true ? '3' : '4'}</g>{'\n'}
<h>{true ? false ? '1' : '' : true ? '3' : '4'}</h>{'\n'}
<i>{false ? true ? '1' : '2' : true ? '3' : '4'}</i>{'\n'}
<j>{false ? true ? '1' : '2' : true ? '' : '4'}</j>{'\n'}
<k>{false ? true ? '1' : '2' : false ? '3' : '4'}</k>
---- CODE ----
'<a>1</a>
<b>1</b>
<c>1</c>
<d>1</d>
<e>1</e>
<f></f>
<g>2</g>
<h></h>
<i>3</i>
<j></j>
<k>4</k>'
---- RESULT ----
<a>1</a>
<b>1</b>
<c>1</c>
<d>1</d>
<e>1</e>
<f></f>
<g>2</g>
<h></h>
<i>3</i>
<j></j>
<k>4</k>
