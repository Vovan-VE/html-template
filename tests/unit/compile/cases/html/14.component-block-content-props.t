Lorem <TestComponent foo=42 bar={lol}>ipsum <div/>{ dolor }</TestComponent> sit
---- CODE ----
('Lorem '.($runtime->createComponent('TestComponent',['foo'=>'42','bar'=>($runtime->param('lol'))],function($runtime){return ('ipsum <div/>'.($runtime::htmlEncode(($runtime::toString(($runtime->param('dolor')))))));})).' sit')
---- RESULT ----
Lorem <!-- Test Component: foo="42" bar="[value of &lol]" -->ipsum <div/>[value of &amp;dolor]<!-- /Test Component --> sit
