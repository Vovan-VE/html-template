Lorem <TestComponent foo=42 bar={lol}>ipsum <div/>{ dolor }</TestComponent> sit
---- CODE ----
('Lorem ' . ($runtime->createComponent('TestComponent',['foo'=>'42','bar'=>($runtime->param('lol'))],function()use($runtime){return ['ipsum ',($runtime::createElement('div')),($runtime::htmlEncode(($runtime->param('dolor'))))];})) . ' sit')
---- RESULT ----
Lorem <!-- Test Component: foo="42" bar="[value of &lol]" -->ipsum <div/>[value of &amp;dolor]<!-- /Test Component --> sit
