<foo id="lorem"  name=ipsum empty-q='' empty-qq="" title='dolor < sit " &#039; & &amp; &rarr; amet > elit ${ as is }' disabled />
---- CODE ----
'<foo id="lorem" name="ipsum" empty-q="" empty-qq="" title="dolor &lt; sit &quot; &#039; &amp; &amp; → amet &gt; elit ${ as is }" disabled/>'
---- RESULT ----
<foo id="lorem" name="ipsum" empty-q="" empty-qq="" title="dolor &lt; sit &quot; &#039; &amp; &amp; → amet &gt; elit ${ as is }" disabled/>
