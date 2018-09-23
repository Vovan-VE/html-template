<foo id="lorem"  name=ipsum title='dolor < sit " &#039; & &amp; &rarr; amet > elit' disabled />
---- CODE ----
($runtime::createElement('foo',['id'=>'lorem','name'=>'ipsum','title'=>'dolor < sit " \' & & → amet > elit','disabled'=>true]))
---- RESULT ----
<foo id="lorem" name="ipsum" title="dolor &lt; sit &quot; &#039; &amp; &amp; → amet &gt; elit" disabled/>
