Lorem { "ipsum ${ dolor } sit ${ "amet" } consectepture ${ "adipisicing ${ elit } sed" } do" } eiusmod
---- CODE ----
('Lorem ipsum '.($runtime::htmlEncode(($runtime::toString(($runtime->param('dolor')))))).' sit amet consectepture adipisicing '.($runtime::htmlEncode(($runtime::toString(($runtime->param('elit')))))).' sed do eiusmod')
---- RESULT ----
Lorem ipsum [value of &amp;dolor] sit amet consectepture adipisicing [value of &amp;elit] sed do eiusmod
