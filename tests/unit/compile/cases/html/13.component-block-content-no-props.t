Lorem <TestComponent>ipsum <div/>{ dolor }</TestComponent> sit
---- CODE ----
('Lorem '.($runtime->createComponent('TestComponent',[],function()use($runtime){return ['ipsum <div/>',($runtime::htmlEncode(($runtime->param('dolor'))))];})).' sit')
---- RESULT ----
Lorem <!-- Test Component: foo=null bar=null -->ipsum <div/>[value of &amp;dolor]<!-- /Test Component --> sit
