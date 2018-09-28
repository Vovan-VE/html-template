Lorem <TestComponent foo=42 bar={lol}></TestComponent> ipsum
---- CODE ----
('Lorem '.($runtime->createComponent('TestComponent',['foo'=>'42','bar'=>($runtime->param('lol'))],[])).' ipsum')
---- RESULT ----
Lorem <!-- Test Component: foo="42" bar="[value of &lol]" --><!-- /Test Component --> ipsum
