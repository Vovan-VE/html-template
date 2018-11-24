Lorem { <div>{ var }</div> } ipsum
---- CODE ----
('Lorem <div>'.($runtime::htmlEncode(($runtime::toString(($runtime->param('var')))))).'</div> ipsum')
---- RESULT ----
Lorem <div>[value of &amp;var]</div> ipsum
