Lorem <TestComponent></TestComponent> ipsum
---- CODE ----
('Lorem ' . ($runtime->createComponent('TestComponent',[],[])) . ' ipsum')
---- RESULT ----
Lorem <!-- Test Component: foo=null bar=null --><!-- /Test Component --> ipsum
