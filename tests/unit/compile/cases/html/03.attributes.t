<foo id="lorem"  name=ipsum title='dolor < sit " &#039; & &amp; &rarr; amet > elit' disabled />
---- OK ----
($runtime::createElement('foo', ['id' => ('lorem'),'name' => 'ipsum','title' => ('dolor < sit " \' & & → amet > elit'),'disabled' => true]))
